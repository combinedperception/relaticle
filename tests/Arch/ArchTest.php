<?php

declare(strict_types=1);

use App\Filament\Exports\BaseExporter;
use App\Filament\Imports\BaseImporter;
use App\Filament\Pages\Import\ImportPage;
use App\Livewire\BaseLivewireComponent;
use App\Mcp\Tools\BaseAttachTool;
use App\Mcp\Tools\BaseCreateTool;
use App\Mcp\Tools\BaseDeleteTool;
use App\Mcp\Tools\BaseDetachTool;
use App\Mcp\Tools\BaseListTool;
use App\Mcp\Tools\BaseShowTool;
use App\Mcp\Tools\BaseUpdateTool;
use App\Models\PersonalAccessToken;
use App\Rules\ArrayExistsForTeam;

arch()->preset()->php();

// arch()->preset()->strict();

arch()->preset()->security()->ignoring('assert');

arch()->preset()
    ->laravel()
    ->ignoring([
        'App\Providers\AppServiceProvider',
        'App\Providers\Filament\AppPanelProvider',
        'Relaticle\Admin\AdminPanelProvider',
        'App\Enums\EnumValues',
        'App\Enums\CustomFields\CustomFieldTrait',
        'App\Mcp',
    ]);

arch('strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('avoid open for extension')
    ->expect('App')
    ->classes()
    ->toBeFinal()
    ->ignoring([
        BaseLivewireComponent::class,
        BaseImporter::class,
        BaseExporter::class,
        BaseListTool::class,
        BaseShowTool::class,
        BaseCreateTool::class,
        BaseUpdateTool::class,
        BaseDeleteTool::class,
        BaseAttachTool::class,
        BaseDetachTool::class,
        ImportPage::class,
        PersonalAccessToken::class,
    ]);

arch('ensure no extends')
    ->expect('App')
    ->classes()
    ->not
    ->toBeAbstract()
    ->ignoring([
        BaseLivewireComponent::class,
        BaseImporter::class,
        BaseExporter::class,
        BaseListTool::class,
        BaseShowTool::class,
        BaseCreateTool::class,
        BaseUpdateTool::class,
        BaseDeleteTool::class,
        BaseAttachTool::class,
        BaseDetachTool::class,
        ImportPage::class,
    ]);

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Console\Commands',
        'App\Exceptions',
        'App\Filament',
        'App\Health',
        'App\Http\Requests',
        'App\Http\Resources',
        'App\Jobs',
        'App\Listeners',
        'App\Livewire',
        'App\Mail',
        'App\Mcp',
        'App\Models',
        'App\Data',
        'App\Notifications',
        'App\Providers',
        'App\View',
        'App\Services\Favicon\Drivers',
        'App\Providers\Filament',
        'App\Scribe',
        ArrayExistsForTeam::class,
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Console\Commands',
        'App\Exceptions',
        'App\Filament',
        'App\Http\Requests',
        'App\Http\Resources',
        'App\Jobs',
        'App\Data',
        'App\Livewire',
        'App\Mail',
        'App\Health',
        'App\Mcp',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\Scribe',
        'App\View',
    ]);

// arch('annotations')
//    ->expect('App')
//    ->toHavePropertiesDocumented()
//    ->toHaveMethodsDocumented();

arch('main app must not depend on SystemAdmin module')
    ->expect('App')
    ->not
    ->toUse('Relaticle\SystemAdmin')
    ->ignoring([
        'App\Providers\AppServiceProvider',
        'App\Console\Commands\InstallCommand',
        'App\Console\Commands\CreateSystemAdminCommand',
        'App\Console\Commands\MakeFilamentUserCommand',
    ]);

arch('SystemAdmin module must not depend on main app namespace')
    ->expect('Relaticle\SystemAdmin')
    ->not
    ->toUse('App')
    ->ignoring([
        'App\Models',
        'App\Enums',
        'App\Rules',
    ]);

arch('API controllers must not use Eloquent query methods directly')
    ->expect('App\Http\Controllers\Api\V1')
    ->not
    ->toUse([
        'Illuminate\Support\Facades\DB',
    ]);

arch('API controllers must depend on actions for write operations')
    ->expect('App\Http\Controllers\Api\V1')
    ->toOnlyUse([
        'App\Actions',
        'App\Enums',
        'App\Http\Requests',
        'App\Http\Resources',
        'App\Models',
        'Illuminate',
        'Knuckles\Scribe',
        'response',
    ]);

arch('MCP tools must not use DB facade directly')
    ->expect('App\Mcp\Tools')
    ->not
    ->toUse([
        'Illuminate\Support\Facades\DB',
    ]);

// Keep raw SQL out of the UI layer — writes belong in action classes
// (app/Actions). The grandfathered files use DB::transaction purely to wrap an
// atomic operation, not to run business-logic queries; new violations are
// blocked. The complementary "no inline $model->update()" rule is enforced by
// the Claude PR reviewer, which Pest's dependency-based arch checks cannot express.
arch('Filament must not use the DB facade directly')
    ->expect('App\Filament')
    ->not
    ->toUse([
        'Illuminate\Support\Facades\DB',
    ])
    ->ignoring([
        // Atomic board-position reorder — UI orchestration of an atomic write.
        'App\Filament\Pages\TasksBoard',
        'App\Filament\Pages\OpportunitiesBoard',
    ]);

arch('Livewire must not use the DB facade directly')
    ->expect('App\Livewire')
    ->not
    ->toUse([
        'Illuminate\Support\Facades\DB',
    ])
    ->ignoring([
        // Atomic personal-access-token creation.
        'App\Livewire\App\AccessTokens\CreateAccessToken',
        // Direct management of the framework sessions table (Jetstream pattern).
        'App\Livewire\App\Profile\LogoutOtherBrowserSessions',
    ]);

arch('must not use custom-fields package models directly')
    ->expect([
        'App',
        'Relaticle\ImportWizard',
        'Relaticle\OnboardSeed',
        'Relaticle\Documentation',
    ])
    ->not
    ->toUse([
        'Relaticle\CustomFields\Models\CustomField',
        'Relaticle\CustomFields\Models\CustomFieldOption',
        'Relaticle\CustomFields\Models\CustomFieldSection',
        'Relaticle\CustomFields\Models\CustomFieldValue',
    ])
    ->ignoring([
        'App\Models\CustomField',
        'App\Models\CustomFieldOption',
        'App\Models\CustomFieldSection',
        'App\Models\CustomFieldValue',
    ]);
