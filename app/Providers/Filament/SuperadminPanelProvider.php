<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
// Import your new widgets here later
use App\Filament\Superadmin\Widgets\GlobalStatsOverview;
use App\Filament\Superadmin\Widgets\TenantSelector;
use App\Filament\Superadmin\Widgets\ContextualStatsOverview;
//use App\Filament\Superadmin\Widgets\ContextualRecentArtworks;
use App\Filament\Superadmin\Widgets\TotalValuePerTenantChart;
use App\Filament\Superadmin\Widgets\TenantArtworkDistributionChart;
//use App\Filament\Superadmin\Widgets\CategoryDistributionChart;

class SuperadminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('superadmin') // Unique ID
            ->path('superadmin') // URL path
            ->login() // Use default login

            // Use the default 'web' guard and 'users' provider/broker
            ->authGuard('web')
            ->authPasswordBroker('users')

            ->colors(['primary' => Color::Red]) // Differentiate visually

            // Define where resources/pages/widgets for THIS panel live
            ->discoverResources(in: app_path('Filament/Superadmin/Resources'), for: 'App\\Filament\\Superadmin\\Resources')
            ->discoverPages(in: app_path('Filament/Superadmin/Pages'), for: 'App\\Filament\\Superadmin\\Pages')
            ->pages([
                Pages\Dashboard::class, // Use Filament's default dashboard page
            ])
            ->discoverWidgets(in: app_path('Filament/Superadmin/Widgets'), for: 'App\\Filament\\Superadmin\\Widgets')
            ->widgets([
                // We will register widgets here later
                //Widgets\FilamentInfoWidget::class, // Example default widget
                TenantSelector::class,          // Selector first
                GlobalStatsOverview::class,     // Then global stats
                ContextualStatsOverview::class, // Then contextual stats
                //ContextualRecentArtworks::class,// Then contextual table
                TotalValuePerTenantChart::class,
                TenantArtworkDistributionChart::class,
               // CategoryDistributionChart::class, // Contextual chart
            ])

            // DO NOT configure ->tenant() or ->tenantMiddleware() for this panel

            ->middleware([ // Standard web middleware
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([ // Middleware for authenticated routes
                Authenticate::class, // Use Filament's default, respects authGuard('web')
                AuthenticateSession::class,
            ]);
    }
}