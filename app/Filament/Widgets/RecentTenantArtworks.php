<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Artwork;           // Import Artwork model
use Filament\Facades\Filament;    // Import Filament facade
use Illuminate\Database\Eloquent\Builder; // Import Builder

class RecentTenantArtworks extends BaseWidget
{
    // Optional: Set how many rows per page (or total limit if pagination disabled)
    protected static ?int $sort = 2; // Display lower on dashboard
    protected int | string | array $columnSpan = 'full'; // Occupy full width

    /**
     * Define the base query for the table, scoped to the current tenant.
     */
    protected function getTableQuery(): Builder
    {
        $tenant = Filament::getTenant();

        // Return query for recent artworks for the current tenant
        // Use optional chaining ($tenant?->id) for safety, though tenant should exist here
        return Artwork::query()
            ->where('tenant_id', $tenant?->id)
            ->latest() // Order by creation date descending
            ->limit(5); // Show only the latest 5
    }

    /**
     * Define the table structure (columns).
     */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('image')
                ->disk('public') // Ensure correct disk is specified
                ->label('Image'),
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('category.name') // Display category name
                ->label('Category')
                ->placeholder('N/A')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Added On')
                ->dateTime()
                ->sortable(),
        ];
    }

     /**
     * Disable pagination for this simple widget table.
     */
    protected function isTablePaginationEnabled(): bool
    {
         return false;
    }

     /**
      * Disable header actions like create button
      */
     protected function getTableHeaderActions(): array
     {
         return [];
     }

      /**
       * Disable bulk actions
       */
      protected function getTableBulkActions(): array
      {
          return [];
      }

       /**
        * Disable filters if not needed
        */
       protected function getTableFilters(): array
       {
           return [];
       }

       /**
        * Optionally disable the header entirely
        */
       // protected function getDefaultTableHeading(): ?string
       // {
       //     return null;
       // }

}