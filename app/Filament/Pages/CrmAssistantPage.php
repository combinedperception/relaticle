<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Jobs\RunPortfolioAnalysisJob;
use App\Models\AgentRun;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
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
        if ($this->runId === null) {
            return null;
        }

        return AgentRun::query()->find($this->runId);
    }
}
