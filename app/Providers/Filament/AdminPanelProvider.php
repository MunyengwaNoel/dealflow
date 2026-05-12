<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Http\Middleware\SetTenantFromUser;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName(config('app.name', 'DealFlow Pro'))
            ->brandLogo(asset('images/dealflow-logo.svg'))
            ->darkModeBrandLogo(asset('images/dealflow-logo-on-dark.svg'))
            ->brandLogoHeight('2.75rem')
            ->font('Plus Jakarta Sans', provider: GoogleFontProvider::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->favicon(asset('images/dealflow-logo.svg'))

            // Colour palette: rich but purposeful
            ->colors([
                'primary' => Color::hex('#2563EB'),
                'info' => Color::hex('#0EA5E9'),
                'success' => Color::hex('#10B981'),
                'warning' => Color::hex('#F59E0B'),
                'danger' => Color::hex('#EF4444'),
                'gray' => Color::Slate,
            ])

            // Sidebar collapses to icons on desktop for more working space
            ->sidebarCollapsibleOnDesktop()

            // Group navigation items so the sidebar is scannable, not a wall of links
            ->navigationGroups([
                NavigationGroup::make('Clients')
                    ->label('Customers')
                    ->icon('heroicon-o-user-group'),
                NavigationGroup::make('Sales')
                    ->label('Sales')
                    ->icon('heroicon-o-presentation-chart-line'),
                NavigationGroup::make('Finance')
                    ->label('Finance')
                    ->icon('heroicon-o-banknotes'),
                NavigationGroup::make('Settings')
                    ->label('Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])

            // Dashboard welcome header
            ->renderHook(
                PanelsRenderHook::PAGE_START,
                fn (): string => request()->routeIs('filament.admin.pages.dashboard')
                    ? view('filament.hooks.dashboard-header')->render()
                    : '',
            )

            // Sign up link above the Filament login form
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => view('filament.hooks.auth-register-link')->render(),
            )

            // Demo credentials card below the login form
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => view('filament.hooks.demo-account-card')->render(),
            )

            // Link back to marketing site from the panel
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn (): string => view('filament.hooks.sidebar-footer')->render(),
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetTenantFromUser::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
