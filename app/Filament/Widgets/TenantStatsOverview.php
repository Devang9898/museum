<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament; // Import the Filament facade
use App\Models\Artwork;      // Import Artwork model
use App\Models\Category;     // Import Category model

class TenantStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get the currently authenticated tenant model
        $tenant = Filament::getTenant();

        // If no tenant context is available (shouldn't happen in normal flow), return empty stats
        if (!$tenant) {
            return [
                Stat::make('Error', 'No tenant context found.')
                    ->color('danger'),
            ];
        }

        // Query counts specific to the current tenant
        $artworkCount = Artwork::query()
            ->where('tenant_id', $tenant->id)
            ->count();

        $categoryCount = Category::query()
            ->where('tenant_id', $tenant->id)
            ->count();

        // Return the stats
        return [
            Stat::make('Total Artworks', $artworkCount)
                ->description('Artworks belonging to ' . $tenant->name)
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary'), // Choose a color
            Stat::make('Total Categories', $categoryCount)
                ->description('Categories belonging to ' . $tenant->name)
                ->descriptionIcon('heroicon-m-tag')
                ->color('success'), // Choose a color
            // Add more tenant-specific stats here if needed
            // e.g., Stat::make('Administrators', $tenant->tenantAdmins()->count()),
        ];
    }

    /**
     * Optional: Control polling interval (how often widget refreshes)
     * Default is null (no polling). Set to a string like '10s', '1m', etc.
     */
    // protected static ?string $pollingInterval = '30s';

    /**
     * Optional: Make the widget occupy the full width
     */
    // protected int | string | array $columnSpan = 'full';
}