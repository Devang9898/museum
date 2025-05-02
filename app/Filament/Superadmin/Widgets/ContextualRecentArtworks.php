<?php

// namespace App\Filament\Superadmin\Widgets; // Correct namespace

// use Filament\Tables;
// use Filament\Tables\Table;
// use Filament\Widgets\TableWidget as BaseWidget;
// use App\Models\Artwork;
// use Illuminate\Database\Eloquent\Builder;
// use Livewire\Attributes\On; // Import the listener attribute

// class ContextualRecentArtworks extends BaseWidget
// {
//     protected static ?int $sort = 1; // Place below stats
//     protected int | string | array $columnSpan = 'full';

//     public ?string $selectedTenantId = null; // Hold selected tenant ID

//     // Update context when event received
//     #[On('tenantSelected')]
//    // #[On('updateRecentArtworks')] // Allow specific refresh
//     public function updateTenantContext($tenantId): void
//     {
//         $this->selectedTenantId = $tenantId;
//     }

//     protected function getTableQuery(): Builder
//     {
//         if ($this->selectedTenantId) {
//             // Tenant-Specific Query
//              return Artwork::query()
//                 ->where('tenant_id', $this->selectedTenantId)
//                 ->with('category') // Eager load category
//                 ->latest()
//                 ->limit(5);
//         } else {
//             // Global Query (show latest across all tenants)
//             return Artwork::query()
//                 ->with(['tenant', 'category']) // Eager load tenant and category
//                 ->latest()
//                 ->limit(10); // Show more when global?
//             // Or return an empty query if preferred when no tenant selected:
//             // return Artwork::query()->whereRaw('1 = 0');
//         }
//     }

//     protected function getTableColumns(): array
//     {
//         $columns = [
//              Tables\Columns\ImageColumn::make('image')->disk('public')->label('Image'),
//              Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
//         ];

//         // Only show Tenant name in global view
//         if (!$this->selectedTenantId) {
//              $columns[] = Tables\Columns\TextColumn::make('tenant.name')
//                             ->label('Tenant')
//                             ->sortable();
//         }

//          $columns[] = Tables\Columns\TextColumn::make('category.name')
//                         ->label('Category')
//                         ->placeholder('N/A')
//                         ->sortable();
//          $columns[] = Tables\Columns\TextColumn::make('created_at')
//                        ->label('Added On')
//                        ->dateTime()
//                        ->sortable();

//          return $columns;
//     }

//     // ... (other table config methods like pagination, actions etc. - keep disabled) ...
//      protected function isTablePaginationEnabled(): bool { return false; }
//      protected function getTableHeaderActions(): array { return []; }
//      protected function getTableBulkActions(): array { return []; }
//      protected function getTableFilters(): array { return []; }
// }