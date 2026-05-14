<?php

declare(strict_types=1);

namespace App\Filament\Pages;

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

    public function runAnalysis(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $team = $user->currentTeam;

        $agentRun = AgentRun::query()->create([
            'team_id' => $team->getKey(),
            'user_id' => $user->getKey(),
            'status' => 'pending',
        ]);

        dispatch(new RunPortfolioAnalysisJob($agentRun, $user));

        $this->runId = $agentRun->id;
        $this->isPolling = true;
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

        /** @var Collection<int, string> $atRiskNames */
        $atRiskNames = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && isset($s['company']))
            ->pluck('company')
            ->unique()
            ->values();

        $notesCreated = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && ($s['entity_type'] ?? '') === 'note')
            ->count();

        $tasksCreated = $steps
            ->filter(fn (array $s): bool => ($s['type'] ?? '') === 'record_created' && ($s['entity_type'] ?? '') === 'task')
            ->count();

        $allCompanies = Company::query()
            ->where('team_id', $teamId)
            ->orderBy('name')
            ->pluck('name');

        $total = $allCompanies->count();
        $atRisk = $atRiskNames->count();
        $healthy = max(0, $total - $atRisk);

        $companies = $allCompanies->map(fn (string $name): array => [
            'name' => $name,
            'risk' => $atRiskNames->contains($name) ? 'at_risk' : 'healthy',
        ])->values()->all();

        return [
            'total' => $total,
            'healthy' => $healthy,
            'at_risk' => $atRisk,
            'notes' => $notesCreated,
            'tasks' => $tasksCreated,
            'actions' => $notesCreated + $tasksCreated,
            'health_score' => $total > 0 ? (int) round($healthy / $total * 100) : 100,
            'companies' => $companies,
        ];
    }
}
