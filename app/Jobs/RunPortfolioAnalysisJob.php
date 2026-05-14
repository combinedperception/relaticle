<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AgentRun;
use App\Models\User;
use App\Services\AI\PortfolioAnalysisAgent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

final class RunPortfolioAnalysisJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public int $tries = 1;

    public int $timeout = 300;

    public function __construct(
        public readonly AgentRun $agentRun,
        public readonly User $user,
    ) {}

    public function handle(PortfolioAnalysisAgent $agent): void
    {
        $this->agentRun->update(['status' => 'running']);

        $agent->run($this->agentRun, $this->user);
    }

    public function failed(): void
    {
        $this->agentRun->update([
            'status' => 'failed',
            'summary' => 'Job failed unexpectedly.',
        ]);
    }
}
