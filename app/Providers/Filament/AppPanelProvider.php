<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Features\SocialAuth;
use App\Filament\Pages\AccessTokens;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\CreateTeam;
use App\Filament\Pages\EditProfile;
use App\Filament\Pages\EditTeam;
use App\Filament\Resources\CompanyResource;
use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\CheckScheduledDeletion;
use App\Listeners\SwitchTeam;
use App\Livewire\App\Profile\ScheduledDeletionInterstitial;
use App\Models\Team;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Jetstream\Features;
use Laravel\Pennant\Feature;
use Relaticle\CustomFields\CustomFieldsPlugin;
use Relaticle\ImportWizard\Filament\Pages\ImportHistory;

final class AppPanelProvider extends PanelProvider
{
    /**
     * Perform post-registration booting of components.
     */
    public function boot(): void
    {
        /**
         * Listen and switch team if tenant was changed
         */
        Event::listen(
            TenantSet::class,
            SwitchTeam::class,
        );

        Action::configureUsing(fn (Action $action): Action => $action->size(Size::Small)->iconPosition('before'));
        DeleteAction::configureUsing(fn (DeleteAction $action): DeleteAction => $action->label('Delete record'));
        Section::configureUsing(fn (Section $section): Section => $section->compact());
        Table::configureUsing(fn (Table $table): Table => $table);
    }

    /**
     * Configure the Filament admin panel.
     *
     * @throws Exception
     */
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('app');

        if ($domain = config('app.app_panel_domain')) {
            $panel->domain($domain);
        } else {
            $panel->path(config('app.app_panel_path', 'app'));
        }

        $panel
            ->homeUrl(fn (): string => CompanyResource::getUrl())
            ->brandName('')
            ->brandLogo(fn (): View|Factory => Auth::user()?->hasVerifiedEmail()
                ? view('filament.app.logo-empty')
                : view('filament.app.logo'))
            ->brandLogoHeight('2.6rem')
            ->login(Login::class)
            ->registration(Register::class)
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->passwordReset()
            ->emailVerification(isRequired: config('app.require_email_verification'))
            ->emailChangeVerification()
            ->strictAuthorization()
            ->databaseNotifications()
            ->colors([
                'primary' => [
                    50 => 'oklch(0.963 0.018 267)',
                    100 => 'oklch(0.929 0.038 267)',
                    200 => 'oklch(0.869 0.074 267)',
                    300 => 'oklch(0.779 0.124 267)',
                    400 => 'oklch(0.678 0.164 267)',
                    500 => 'oklch(0.578 0.196 267)',
                    600 => 'oklch(0.511 0.207 267)',
                    700 => 'oklch(0.452 0.196 267)',
                    800 => 'oklch(0.381 0.164 267)',
                    900 => 'oklch(0.308 0.124 267)',
                    950 => 'oklch(0.226 0.074 252)',
                    'DEFAULT' => 'oklch(0.511 0.207 267)',
                ],
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->userMenuItems([
                Action::make('profile')
                    ->label('Profile')
                    ->icon('heroicon-m-user-circle')
                    ->url(fn (): string => $this->shouldRegisterMenuItem()
                        ? url(EditProfile::getUrl())
                        : url($panel->getPath())),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverPages(in: base_path('packages/ImportWizard/src/Filament/Pages'), for: 'Relaticle\\ImportWizard\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->readOnlyRelationManagersOnResourceViewPagesByDefault(false)
            ->pages([
                EditProfile::class,
                AccessTokens::class,
            ])
            ->spa()
            ->routes(function (): void {
                Route::get('/scheduled-deletion', ScheduledDeletionInterstitial::class)
                    ->middleware('auth')
                    ->name('scheduled-deletion');
            })
            ->breadcrumbs(false)
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Tasks')
                    ->icon('heroicon-o-shopping-cart'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->authMiddleware([
                Authenticate::class,
                CheckScheduledDeletion::class,
            ])
            ->tenantMiddleware(
                [
                    ApplyTenantScopes::class,
                ],
                isPersistent: true
            )
            ->plugins([
                CustomFieldsPlugin::make()
                    ->authorize(fn () => Gate::check('update', Filament::getTenant())),
                ResizedColumnPlugin::make(),
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('@env(\'local\')<x-login-link email="manuk.minasyan1@gmail.com" redirect-url="'.url()->getAppUrl().'" />@endenv'),
            );

        if (Feature::active(SocialAuth::class)) {
            $panel
                ->renderHook(
                    PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                    fn (): View|Factory => view('filament.auth.social_login_buttons')
                )
                ->renderHook(
                    PanelsRenderHook::AUTH_REGISTER_FORM_BEFORE,
                    fn (): View|Factory => view('filament.auth.social_login_buttons')
                );
        }

        $panel
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View|Factory => view('filament.app.analytics')
            );

        if (Features::hasApiFeatures()) {
            $panel->userMenuItems([
                Action::make('api_tokens')
                    ->label(__('access-tokens.user_menu'))
                    ->icon('heroicon-o-key')
                    ->url(fn (): string => $this->shouldRegisterMenuItem()
                        ? url(AccessTokens::getUrl())
                        : url($panel->getPath())),
            ]);
        }

        $panel
            ->tenant(Team::class, slugAttribute: 'slug', ownershipRelationship: 'team')
            ->tenantRegistration(CreateTeam::class)
            ->tenantProfile(EditTeam::class)
            ->tenantMenuItems([
                Action::make('import_history')
                    ->label('Import History')
                    ->icon(Heroicon::OutlinedClock)
                    ->url(fn (): string => ImportHistory::getUrl()),
            ]);

        return $panel;
    }

    public function shouldRegisterMenuItem(): bool
    {
        $hasVerifiedEmail = Auth::user()?->hasVerifiedEmail();

        return Filament::hasTenancy()
            ? $hasVerifiedEmail && Filament::getTenant()
            : $hasVerifiedEmail;
    }
}
