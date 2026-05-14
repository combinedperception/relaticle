<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Fortify;

final readonly class LogAccountLockout
{
    public function handle(Lockout $event): void
    {
        Log::warning('Login attempts throttled', [
            'email' => $event->request->input(Fortify::username(), 'unknown'),
            'ip' => $event->request->ip(),
        ]);
    }
}
