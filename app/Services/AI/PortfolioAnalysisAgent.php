<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Actions\Note\CreateNote;
use App\Actions\Task\CreateTask;
use App\Enums\CreationSource;
use App\Models\AgentRun;
use App\Models\Company;
use App\Models\Note;
use App\Models\Opportunity;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Tool;
use Throwable;

final readonly class PortfolioAnalysisAgent
{
    public function __construct(
        private CreateNote $createNote,
        private CreateTask $createTask,
    ) {}

    public function run(AgentRun $agentRun, User $actor, string $mode = 'insights'): void
    {
        Auth::login($actor);

        try {
            $team = $actor->currentTeam;

            $isInsights = $mode === 'insights';

            $response = Prism::text()
                ->using(Provider::Anthropic, 'claude-sonnet-4-6')
                ->withTools($isInsights
                    ? $this->buildInsightsTools($agentRun, $team)
                    : $this->buildFullTools($agentRun, $actor, $team)
                )
                ->withMaxSteps(15)
                ->withSystemPrompt($isInsights ? $this->insightsSystemPrompt() : $this->fullSystemPrompt())
                ->withPrompt($isInsights
                    ? 'Analyse the full portfolio. Flag each at-risk company using flag_company_at_risk. End with a plain-text portfolio health summary.'
                    : 'Analyse the full portfolio. Flag at-risk companies, create notes and tasks for each. End with a plain-text portfolio health summary.'
                )
                ->generate();

            $agentRun->update([
                'status' => 'completed',
                'summary' => $response->text,
            ]);
        } catch (Throwable $e) {
            $agentRun->update([
                'status' => 'failed',
                'summary' => 'Analysis failed: '.$e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Read-only tools for insights mode. Includes flag_company_at_risk instead of write tools.
     *
     * @return array<int, Tool>
     */
    private function buildInsightsTools(AgentRun $agentRun, Team $team): array
    {
        $teamId = $team->getKey();

        return [
            ...$this->readTools($agentRun, $teamId),

            (new Tool)
                ->as('flag_company_at_risk')
                ->for('Flag a company as at-risk in the portfolio health report. Does not create any CRM records.')
                ->withStringParameter('company_id', 'The company ID returned by list_portfolio_companies')
                ->withStringParameter('reason', 'One to two sentences explaining the specific risk — professional tone')
                ->withStringParameter('risk_level', 'Risk severity: "high" (no pipeline and silent), "medium" (stalled opp or no recent notes), or "low" (minor concern)')
                ->using(function (string $company_id, string $reason, string $risk_level) use ($agentRun, $teamId): string {
                    $company = Company::query()
                        ->where('team_id', $teamId)
                        ->findOrFail($company_id);

                    $agentRun->appendStep([
                        'type' => 'company_at_risk',
                        'company' => $company->name,
                        'company_id' => $company_id,
                        'reason' => $reason,
                        'risk_level' => $risk_level,
                        'summary' => "[{$risk_level}] {$company->name}: {$reason}",
                    ]);

                    return "Flagged {$company->name} as {$risk_level} risk.";
                }),
        ];
    }

    /**
     * Full tools for write mode. Includes create_company_note and create_follow_up_task.
     *
     * @return array<int, Tool>
     */
    private function buildFullTools(AgentRun $agentRun, User $actor, Team $team): array
    {
        $teamId = $team->getKey();

        return [
            ...$this->readTools($agentRun, $teamId),

            (new Tool)
                ->as('create_company_note')
                ->for('Create a note on a company summarising the risk assessment and recommended action')
                ->withStringParameter('company_id', 'The company ID to attach the note to')
                ->withStringParameter('content', 'The note content — keep under 3 sentences, professional tone')
                ->using(function (string $company_id, string $content) use ($agentRun, $actor, $teamId): string {
                    $company = Company::query()
                        ->where('team_id', $teamId)
                        ->findOrFail($company_id);

                    $agentRun->appendStep(['type' => 'tool_call', 'tool' => 'create_company_note', 'summary' => "Creating note on {$company->name}"]);

                    $note = $this->createNote->execute($actor, [
                        'title' => $content,
                        'company_ids' => [$company_id],
                    ], CreationSource::MCP);

                    $agentRun->appendStep([
                        'type' => 'record_created',
                        'entity_type' => 'note',
                        'entity_id' => $note->getKey(),
                        'company' => $company->name,
                        'summary' => "Note created on {$company->name}",
                        'content' => $content,
                    ]);

                    return "Note created on {$company->name} (id: {$note->getKey()})";
                }),

            (new Tool)
                ->as('create_follow_up_task')
                ->for('Create a follow-up task for a company with a specific action item')
                ->withStringParameter('company_id', 'The company ID to link the task to')
                ->withStringParameter('title', 'A specific, actionable task title')
                ->using(function (string $company_id, string $title) use ($agentRun, $actor, $teamId): string {
                    $company = Company::query()
                        ->where('team_id', $teamId)
                        ->findOrFail($company_id);

                    $agentRun->appendStep(['type' => 'tool_call', 'tool' => 'create_follow_up_task', 'summary' => "Creating task for {$company->name}"]);

                    $task = $this->createTask->execute($actor, [
                        'title' => $title,
                        'company_ids' => [$company_id],
                    ], CreationSource::MCP);

                    $agentRun->appendStep([
                        'type' => 'record_created',
                        'entity_type' => 'task',
                        'entity_id' => $task->getKey(),
                        'company' => $company->name,
                        'summary' => "Task created for {$company->name}",
                        'content' => $title,
                    ]);

                    return "Task created for {$company->name} (id: {$task->getKey()})";
                }),
        ];
    }

    /**
     * Read-only tools shared by both modes.
     *
     * @return array<int, Tool>
     */
    private function readTools(AgentRun $agentRun, string $teamId): array
    {
        return [
            (new Tool)
                ->as('list_portfolio_companies')
                ->for('Get an overview of all portfolio companies with last contact date, open opportunities, and pending tasks')
                ->using(function () use ($agentRun, $teamId): string {
                    $agentRun->appendStep(['type' => 'tool_call', 'tool' => 'list_portfolio_companies', 'summary' => 'Fetching portfolio overview']);

                    $companies = Company::query()
                        ->where('team_id', $teamId)
                        ->with(['notes' => fn ($q) => $q->orderByDesc('notes.created_at')->limit(1)])
                        ->withCount(['opportunities', 'tasks'])
                        ->get()
                        ->map(fn (Company $c): array => [
                            'id' => $c->getKey(),
                            'name' => $c->name,
                            'last_note_date' => $c->notes->first()?->created_at?->toDateString(),
                            'days_since_contact' => $c->notes->first()?->created_at
                                ? (int) $c->notes->first()->created_at->diffInDays(now())
                                : null,
                            'open_opportunities' => $c->opportunities_count,
                            'pending_tasks' => $c->tasks_count,
                        ]);

                    $result = $companies->toJson();
                    $agentRun->appendStep(['type' => 'tool_result', 'tool' => 'list_portfolio_companies', 'summary' => $companies->count().' companies found']);

                    return $result;
                }),

            (new Tool)
                ->as('list_opportunities')
                ->for('List all open opportunities with linked company and days since last update')
                ->using(function () use ($agentRun, $teamId): string {
                    $agentRun->appendStep(['type' => 'tool_call', 'tool' => 'list_opportunities', 'summary' => 'Fetching all opportunities']);

                    $opportunities = Opportunity::query()
                        ->where('team_id', $teamId)
                        ->with('company')
                        ->latest('updated_at')
                        ->get()
                        ->map(fn (Opportunity $o): array => [
                            'id' => $o->getKey(),
                            'name' => $o->name,
                            'company' => $o->company?->name,
                            'days_since_updated' => (int) $o->updated_at->diffInDays(now()),
                        ]);

                    $result = $opportunities->toJson();
                    $agentRun->appendStep(['type' => 'tool_result', 'tool' => 'list_opportunities', 'summary' => $opportunities->count().' opportunities']);

                    return $result;
                }),

            (new Tool)
                ->as('list_overdue_tasks')
                ->for('List tasks that have been open for more than 14 days, linked to their companies')
                ->using(function () use ($agentRun, $teamId): string {
                    $agentRun->appendStep(['type' => 'tool_call', 'tool' => 'list_overdue_tasks', 'summary' => 'Fetching overdue tasks']);

                    $tasks = Task::query()
                        ->where('team_id', $teamId)
                        ->where('created_at', '<', now()->subDays(14))
                        ->with('companies')
                        ->get()
                        ->map(fn (Task $t): array => [
                            'id' => $t->getKey(),
                            'title' => $t->title,
                            'days_open' => (int) $t->created_at->diffInDays(now()),
                            'companies' => $t->companies->pluck('name')->all(),
                        ]);

                    $result = $tasks->toJson();
                    $agentRun->appendStep(['type' => 'tool_result', 'tool' => 'list_overdue_tasks', 'summary' => $tasks->count().' overdue tasks']);

                    return $result;
                }),

            (new Tool)
                ->as('get_company_details')
                ->for('Get full context for a specific company: last 3 notes, open opportunities, and pending tasks')
                ->withStringParameter('company_id', 'The company ID returned by list_portfolio_companies')
                ->using(function (string $company_id) use ($agentRun, $teamId): string {
                    $agentRun->appendStep(['type' => 'tool_call', 'tool' => 'get_company_details', 'summary' => "Getting details for company {$company_id}"]);

                    $company = Company::query()
                        ->where('team_id', $teamId)
                        ->with([
                            'notes' => fn ($q) => $q->orderByDesc('notes.created_at')->limit(3),
                            'opportunities',
                            'tasks',
                        ])
                        ->findOrFail($company_id);

                    $details = [
                        'id' => $company->getKey(),
                        'name' => $company->name,
                        'recent_notes' => $company->notes->map(fn (Note $n): array => [
                            'title' => $n->title,
                            'date' => $n->created_at->toDateString(),
                            'days_ago' => (int) $n->created_at->diffInDays(now()),
                        ])->all(),
                        'opportunities' => $company->opportunities->map(fn (Opportunity $o): array => [
                            'name' => $o->name,
                            'days_since_updated' => (int) $o->updated_at->diffInDays(now()),
                        ])->all(),
                        'tasks' => $company->tasks->map(fn (Task $t): array => [
                            'title' => $t->title,
                            'days_open' => (int) $t->created_at->diffInDays(now()),
                        ])->all(),
                    ];

                    $agentRun->appendStep(['type' => 'tool_result', 'tool' => 'get_company_details', 'summary' => "Details loaded for {$company->name}"]);

                    return json_encode($details) ?: '{}';
                }),
        ];
    }

    private function insightsSystemPrompt(): string
    {
        return <<<'PROMPT'
        You are a CRM Portfolio Intelligence assistant for an investment firm.

        Analyse the entire portfolio systematically:
        1. Call list_portfolio_companies to get an overview of all companies
        2. Call list_opportunities and list_overdue_tasks for context
        3. For companies with no recent contact (>30 days) or stalled opportunities (>30 days since update), call get_company_details
        4. For each at-risk company, call flag_company_at_risk with:
           - company_id: the exact ID from list_portfolio_companies
           - reason: 1–2 sentences explaining the specific risk (professional tone)
           - risk_level: "high" (no pipeline and silent >30 days), "medium" (stalled opp or missing recent notes), "low" (minor concern)
        5. End with a plain-text portfolio health summary: overall status, count of at-risk companies, key findings

        Rules:
        - Maximum 15 tool calls total
        - Do NOT create notes or tasks — flag only
        - Be decisive, not verbose
        PROMPT;
    }

    private function fullSystemPrompt(): string
    {
        return <<<'PROMPT'
        You are a CRM Portfolio Intelligence assistant for an investment firm.

        Analyse the entire portfolio systematically:
        1. Call list_portfolio_companies to get an overview of all companies
        2. Call list_opportunities and list_overdue_tasks for context
        3. For companies with no recent contact (>30 days) or stalled opportunities (>30 days since update), call get_company_details
        4. Create a brief note on each at-risk company summarising the risk and recommended action
        5. Create a follow-up task for each at-risk company with a specific action item
        6. End with a plain-text portfolio health summary: overall status, count of at-risk companies, key actions taken

        Rules:
        - Maximum 15 tool calls total
        - Create notes and tasks — do not just recommend them
        - Keep notes under 3 sentences, professional tone
        - Be decisive, not verbose
        PROMPT;
    }
}
