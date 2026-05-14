<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Actions\Note\CreateNote;
use App\Actions\Task\CreateTask;
use App\Enums\CreationSource;
use App\Jobs\RunPortfolioAnalysisJob;
use App\Models\AgentRun;
use App\Models\Company;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use UnitEnum;

final class CrmAssistantPage extends Page
{
    protected string $view = 'filament.pages.crm-assistant';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|UnitEnum|null $navigationGroup = 'AI';

    protected static ?string $navigationLabel = 'CRM Assistant';

    protected static ?string $title = 'CRM Assistant';

    protected static ?int $navigationSort = 1;

    public ?int $runId = null;

    public bool $isPolling = false;

    public function runInsights(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $team = $user->currentTeam;

        $agentRun = AgentRun::query()->create([
            'team_id' => $team->getKey(),
            'user_id' => $user->getKey(),
            'status' => 'pending',
        ]);

        dispatch(new RunPortfolioAnalysisJob($agentRun, $user, 'insights'));

        $this->runId = $agentRun->id;
        $this->isPolling = true;
    }

    public function applyRecommendations(): void
    {
        $run = $this->getAgentRun();

        if (! $run?->isFinished()) {
            return;
        }

        /** @var User $user */
        $user = auth()->user();
        $teamId = $user->currentTeam->getKey();

        $createNote = app(CreateNote::class);
        $createTask = app(CreateTask::class);

        $flagged = collect($run->steps ?? [])
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'company_at_risk');

        foreach ($flagged as $flaggedStep) {
            /** @var Company|null $company */
            $company = Company::query()
                ->where('team_id', $teamId)
                ->find($flaggedStep['company_id']);

            if (! $company) {
                continue;
            }

            $note = $createNote->execute($user, [
                'title' => $flaggedStep['reason'],
                'company_ids' => [$company->getKey()],
            ], CreationSource::MCP);

            $run->appendStep([
                'type' => 'record_created',
                'entity_type' => 'note',
                'entity_id' => $note->getKey(),
                'company' => $company->name,
                'summary' => "Note created on {$company->name}",
                'content' => $flaggedStep['reason'],
            ]);

            $task = $createTask->execute($user, [
                'title' => "Follow up: {$company->name} ({$flaggedStep['risk_level']} risk)",
                'company_ids' => [$company->getKey()],
            ], CreationSource::MCP);

            $run->appendStep([
                'type' => 'record_created',
                'entity_type' => 'task',
                'entity_id' => $task->getKey(),
                'company' => $company->name,
                'summary' => "Task created for {$company->name}",
                'content' => "Follow up: {$company->name}",
            ]);
        }
    }

    public function checkStatus(): void
    {
        $run = $this->getAgentRun();

        if ($run?->isFinished()) {
            $this->isPolling = false;
        }
    }

    public function getAgentRun(): ?AgentRun
    {
        if ($this->runId !== null) {
            return AgentRun::query()->find($this->runId);
        }

        /** @var User $user */
        $user = auth()->user();

        return AgentRun::query()
            ->where('team_id', $user->currentTeam->getKey())
            ->whereIn('status', ['completed', 'failed', 'pending', 'running'])
            ->latest()
            ->first();
    }

    public function hasPendingRecommendations(): bool
    {
        $run = $this->getAgentRun();

        if (! $run?->isFinished()) {
            return false;
        }

        $steps = collect($run->steps ?? []);

        /** @var Collection<int, string> $flaggedNames */
        $flaggedNames = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'company_at_risk')
            ->pluck('company')
            ->unique();

        if ($flaggedNames->isEmpty()) {
            return false;
        }

        /** @var Collection<int, string> $appliedNames */
        $appliedNames = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && isset($s['company']))
            ->pluck('company')
            ->unique();

        return $flaggedNames->diff($appliedNames)->isNotEmpty();
    }

    /**
     * Returns structured analytics derived from the agent run's steps array
     * and a live company count. Returns empty array when no finished run exists.
     *
     * @return array<string, mixed>
     */
    public function getRunSummary(): array
    {
        $run = $this->getAgentRun();

        if (! $run?->isFinished()) {
            return [];
        }

        /** @var User $user */
        $user = auth()->user();
        $teamId = $user->currentTeam->getKey();

        $steps = collect($run->steps ?? []);

        /** @var Collection<int, array<string, string>> $flaggedSteps */
        $flaggedSteps = $steps->filter(fn (array $s): bool => ($s['type'] ?? '') === 'company_at_risk');

        /** @var Collection<int, string> $flaggedNames */
        $flaggedNames = $flaggedSteps->pluck('company')->unique()->values();

        /** @var Collection<int, string> $appliedNames */
        $appliedNames = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && isset($s['company']))
            ->pluck('company')
            ->unique()
            ->values();

        // Union of flagged + applied covers both insights runs and legacy full runs
        /** @var Collection<int, string> $atRiskNames */
        $atRiskNames = $flaggedNames->merge($appliedNames)->unique()->values();

        $notesCreated = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && ($s['entity_type'] ?? '') === 'note')
            ->count();

        $tasksCreated = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && ($s['entity_type'] ?? '') === 'task')
            ->count();

        /** @var array<string, string> $riskLevels */
        $riskLevels = $flaggedSteps->pluck('risk_level', 'company')->all();

        $allCompanies = Company::query()
            ->where('team_id', $teamId)
            ->orderBy('name')
            ->pluck('name');

        $total = $allCompanies->count();
        $atRisk = $atRiskNames->count();
        $healthy = max(0, $total - $atRisk);

        $companies = $allCompanies->map(function (string $name) use ($atRiskNames, $appliedNames, $riskLevels): array {
            if (! $atRiskNames->contains($name)) {
                return ['name' => $name, 'risk' => 'healthy', 'risk_level' => null, 'applied' => false];
            }

            $applied = $appliedNames->contains($name);
            $riskLevel = $riskLevels[$name] ?? 'medium';

            return [
                'name' => $name,
                'risk' => $applied ? 'applied' : $riskLevel,
                'risk_level' => $riskLevel,
                'applied' => $applied,
            ];
        })->values()->all();

        return [
            'total' => $total,
            'healthy' => $healthy,
            'at_risk' => $atRisk,
            'notes' => $notesCreated,
            'tasks' => $tasksCreated,
            'actions' => $notesCreated + $tasksCreated,
            'health_score' => $total > 0 ? (int) round($healthy / $total * 100) : 100,
            'companies' => $companies,
            'has_pending_recommendations' => $flaggedNames->isNotEmpty() && $flaggedNames->diff($appliedNames)->isNotEmpty(),
        ];
    }
}
