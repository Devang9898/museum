<?php

namespace App\Filament\Superadmin\Widgets; // Correct namespace

use Filament\Widgets\Widget;
use App\Models\Tenant;
use Livewire\Attributes\Locked; // For protecting property
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class TenantSelector extends Widget
{
    protected static string $view = 'filament.superadmin.widgets.tenant-selector'; // Correct view path
    protected int | string | array $columnSpan = 'full';
    // Property to hold the selected tenant's ID. Null means "All/Global"
    //#[Locked] // Prevent modification from frontend except via wire:model
    public ?string $selectedTenantId = null;

    // Property to hold tenant options for the select dropdown
    public array $tenantOptions = [];

    // Ran when the component is initialized
    public function mount(): void
    {
        $this->loadTenantOptions();
    }

    // Load tenant options from the database
    protected function loadTenantOptions(): void
    {
        $this->tenantOptions = Tenant::orderBy('name')
                                  ->pluck('name', 'id') // Get name as label, id as value
                                  ->toArray();
    }

    // Livewire hook called when $selectedTenantId is updated by the select dropdown
    public function updatedSelectedTenantId($value): void
    {
        Log::debug('Dispatching tenantSelected event', ['tenantId_value' => $this->selectedTenantId]);
        // Ensure value is null if "all" is selected, otherwise it's the UUID string
        $this->selectedTenantId = ($value === '' || $value === 'all') ? null : $value;

        // Dispatch an event to notify other widgets
        $this->dispatch('tenantSelected',  $this->selectedTenantId);
         $this->dispatch('updateStats'); // Specific event for stats widget
         $this->dispatch('updateRecentArtworks'); // Specific event for artworks widget
    }

     // Make widget appear higher on the dashboard
     protected static ?int $sort = -3; // Use negative numbers to place higher
     
}