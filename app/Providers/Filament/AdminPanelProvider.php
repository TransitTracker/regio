<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Tenancy\EditAgencyProfile;
use App\Filament\Pages\Tenancy\RegisterAgency;
use App\Models\Gtfs\Agency;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(env('FILAMENT_PATH', 'admin'))
            ->domain(env('FILAMENT_DOMAIN'))
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'primary' => Color::hex('#00639a'),
                'success' => Color::Emerald,
                'warning' => Color::Yellow,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->font('Inter')
            ->databaseNotifications()
            ->navigationGroups([
                'GTFS Creator',
                'Helpers',
                'Admin',
            ])
            ->collapsibleNavigationGroups(false)
            ->navigationItems([
                NavigationItem::make('Logs viewer')
                    ->url(fn (): string => route('log-viewer.index'), shouldOpenInNewTab: true)
                    ->group('Admin')
                    ->icon('mdi-alert-circle'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Transit Tracker Regio')
            ->tenant(Agency::class)
            ->tenantRegistration(RegisterAgency::class)
            ->tenantProfile(EditAgencyProfile::class);
    }
}
