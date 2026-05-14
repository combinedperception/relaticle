<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Fortify;

final readonly class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $usernameField = Fortify::username();

        Log::warning('Failed login attempt', [
            'email' => $event->credentials[$usernameField] ?? 'unknown',
            'ip' => request()->ip(),
            'guard' => $event->guard,
            'user_exists' => $event->user !== null,
        ]);
    }
}
