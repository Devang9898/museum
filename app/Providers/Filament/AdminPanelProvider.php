<?php

namespace App\Providers\Filament;

// Base Filament & Laravel Middleware/Components
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
use Illuminate\Session\Middleware\AuthenticateSession; // Needed for authMiddleware
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;


// Custom Page for Registration
use App\Filament\Pages\RegisterTenant; // Import your custom registration page

// Multi-Tenancy Related
use App\Models\Tenant;
use Filament\Http\Middleware\IdentifyTenant; // Filament's tenant identifier
use App\Http\Middleware\ApplyTenantScopes; // Your custom tenant scope middleware
use App\Filament\Widgets\TenantStatsOverview; // <-- CORRECT Namespace
use App\Filament\Widgets\RecentTenantArtworks; // <-- CORRECT Namespace
// use App\Filament\Widgets\TenantArtworkStat;
// use App\Filament\Widgets\TenantCategoryChart;
// use App\Filament\Widgets\TenantPriceChart;
// use App\Filament\Widgets\TenantCategoryValueChart; // <-- Import new widget

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin') // Panel ID
            ->path('admin') // URL Path

            // --- Authentication Routes ---
            ->login() // Enable the default login page
            ->registration(RegisterTenant::class) // Use the custom page for registration
            // ->passwordReset() // Uncomment if you implement password resets
            // ->emailVerification() // Uncomment if you implement email verification
            // ->profile() // Uncomment to enable user profile page (may need customization)

            ->colors([
                'primary' => Color::Amber, // Theme color
            ])

            // --- Resource/Page/Widget Discovery ---
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class, // Default dashboard
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Register default or custom widgets here
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                TenantStatsOverview::class,
                RecentTenantArtworks::class,
                // TenantArtworkStat::class,
                // TenantCategoryChart::class,
                // TenantCategoryValueChart::class,
                // TenantPriceChart::class,
            ])

            // --- Multi-Tenancy Configuration ---
            ->tenant(
                model: Tenant::class,
                slugAttribute: 'slug',
                ownershipRelationship: 'tenant'
             )
            ->tenantMenu() // Show the tenant switcher menu
            // Middleware specific to tenant context (runs after tenant identified)
            ->tenantMiddleware([
                ApplyTenantScopes::class, // Apply your custom global scopes first
                IdentifyTenant::class    // Then let Filament identify the tenant
            ], isPersistent: true) // Remember the selected tenant across requests

            // --- Authentication Configuration ---
            ->authGuard('tenant_admin')       // Use the 'tenant_admin' guard
            ->authPasswordBroker('tenant_admins') // Use the corresponding password broker

            // --- Core Middleware Pipeline (Applies to ALL panel routes, including guest routes) ---
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class, // Start session for all requests
                // AuthenticateSession::class, // DO NOT apply session auth globally for guest routes
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // \App\Http\Middleware\EnsureTenancyIsReady::class, // Optional placeholder
            ])

            // --- Authenticated Middleware Pipeline (Applies ONLY after successful login) ---
            ->authMiddleware([
                Authenticate::class,          // Ensure user is logged in via 'tenant_admin' guard
                AuthenticateSession::class, // Apply session authentication *after* user is confirmed logged in
                // Add other middleware for authenticated users if needed (e.g., email verification check)
            ]);
    }
}