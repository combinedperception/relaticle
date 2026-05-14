<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property array<int, array<string, mixed>> $steps
 */
final class AgentRun extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'status',
        'steps',
        'summary',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'steps' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    public function isFinished(): bool
    {
        return in_array($this->status, ['completed', 'failed'], true);
    }

    /**
     * @param  array<string, mixed>  $step
     */
    public function appendStep(array $step): void
    {
        $steps = $this->steps;
        $steps[] = array_merge($step, ['timestamp' => now()->toISOString()]);
        $this->update(['steps' => $steps]);
    }
}
