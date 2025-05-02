<?php

namespace App\Filament\Superadmin\Widgets; // Note the namespace

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Tenant;    // For counting tenants
use App\Models\Artwork;   // For counting total artworks
use App\Models\Category;  // For counting total categories
use App\Models\TenantAdmin; // For counting tenant admins

class GlobalStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Organizations', Tenant::count())
                ->description('All registered tenants')
                ->color('primary'),
            Stat::make('Total Tenant Admins', TenantAdmin::count())
                 ->description('Across all tenants')
                 ->color('info'),
            Stat::make('Total Artworks (All)', Artwork::count())
                 ->description('Across all tenants')
                 ->color('success'),
            Stat::make('Total Categories (All)', Category::count())
                ->description('Across all tenants')
                ->color('warning'),
        ];
    }
}