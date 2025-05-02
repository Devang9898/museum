<?php

namespace App\Filament\Superadmin\Widgets; // Correct namespace

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Tenant; // Need Tenant model to get name
use Livewire\Attributes\On; // Import the listener attribute

class ContextualStatsOverview extends BaseWidget
{
    // Property to hold the currently selected tenant ID from the selector widget
    public ?string $selectedTenantId = null;

    // Method to update the tenant ID when the event is received
    #[On('tenantSelected')] // Listen for the event dispatched by TenantSelector
    public function updateTenantContext($tenantId): void
    {
        $this->selectedTenantId = $tenantId;
    }

     #[On('updateStats')] // Listen for specific refresh event
     public function refreshStats(): void
     {
          // This method is just a placeholder to trigger a refresh
          // when the corresponding dispatch happens. Livewire handles the rest.
     }


    protected function getStats(): array
    {
        if ($this->selectedTenantId) {
            // --- Tenant-Specific Stats ---
            $tenant = Tenant::find($this->selectedTenantId); // Find selected tenant
            if (!$tenant) {
                return [Stat::make('Error', 'Selected Tenant Not Found')->color('danger')];
            }

            $artworkCount = Artwork::where('tenant_id', $this->selectedTenantId)->count();
            $categoryCount = Category::where('tenant_id', $this->selectedTenantId)->count();

            return [
                Stat::make("Artworks for {$tenant->name}", $artworkCount)
                     ->descriptionIcon('heroicon-m-photo')
                     ->color('info'),
                Stat::make("Categories for {$tenant->name}", $categoryCount)
                     ->descriptionIcon('heroicon-m-tag')
                     ->color('success'),
            ];
        } else {
            // --- Global Stats (or show nothing/message) ---
            // Replicating from GlobalStatsOverview for consistency, or could show different global stats
            return [
                Stat::make('Total Artworks (All)', Artwork::count())
                     ->description('Across all tenants')
                     ->color('success'),
                Stat::make('Total Categories (All)', Category::count())
                     ->description('Across all tenants')
                     ->color('warning'),
            ];
             // Or: return []; // Show nothing if no tenant selected
             // Or: return [Stat::make('Select a Tenant', 'Use the dropdown above')->color('gray')];
        }
    }
     protected static ?int $sort = -1; // Place below Global Stats
}