<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtworkResource\Pages;
// use App\Filament\Resources\ArtworkResource\RelationManagers; // Uncomment if you add relation managers later
use App\Models\Artwork;
use App\Models\Category; // Needed for the relationship query
use Filament\Facades\Filament; // Needed for getting the current tenant
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // Needed for modifyQueryUsing
// use Illuminate\Database\Eloquent\SoftDeletingScope; // Uncomment if using soft deletes

class ArtworkResource extends Resource
{
    protected static ?string $model = Artwork::class;

    // Use the more appropriate icon from the second example
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // tenant_id is handled automatically by Filament's multi-tenancy configuration
                // and the mutateFormDataBeforeCreate method below.

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                // Use the relationship method with query modification for tenant-specific categories
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship(
                        name: 'category', // Relationship method name on the Artwork model
                        titleAttribute: 'name', // Attribute on Category model to display
                        // Modify the query to only show categories belonging to the current tenant
                        modifyQueryUsing: function (Builder $query) {
                            $tenant = Filament::getTenant();
                            if ($tenant) {
                                $query->where('tenant_id', $tenant->id);
                            } else {
                                // Fallback if tenant isn't resolved (shouldn't happen in normal flow)
                                // Return no results to prevent showing categories from other tenants
                                $query->whereRaw('1 = 0');
                            }
                        }
                    )
                    ->searchable() // Allow searching within the dropdown
                    ->preload()    // Preload options for better UX if category list isn't huge
                    ->nullable(), // Allow selecting no category

                Forms\Components\FileUpload::make('image')
                    ->label('Artwork Image')
                    ->image() // Validate as image, provide preview
                    ->directory('artworks') // Subdirectory in storage/app/public
                    ->visibility('public') // Store in the public disk
                    ->nullable(), // Allow not uploading an image
            ]);
    }

    public static function table(Table $table): Table
    {
        // The base query is automatically scoped by Filament's panel tenancy config
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image')
                    ->disk('public'), // Specify the public disk

                Tables\Columns\TextColumn::make('category.name') // Display related category name
                    ->label('Category')
                    ->sortable()
                    ->searchable() // Allow searching by category name
                    ->placeholder('N/A'), // Display if category is null

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default
            ])
            ->filters([
                // Filter artworks by their (tenant-specific) category
                Tables\Filters\SelectFilter::make('category')
                    ->relationship(
                        'category',
                        'name',
                        // Ensure filter dropdown also only shows categories for the current tenant
                        modifyQueryUsing: fn (Builder $query) => $query->where('tenant_id', Filament::getTenant()?->id)
                    )
                    ->searchable()
                    ->preload()
                    ->label('Filter by Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any Relation Managers here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtworks::route('/'),
            'create' => Pages\CreateArtwork::route('/create'),
            'edit' => Pages\EditArtwork::route('/{record}/edit'),
        ];
    }

    /**
     * Mutate form data before it is used to create the record.
     * Ensures the tenant_id is automatically set based on the current context.
     */
     public static function mutateFormDataBeforeCreate(array $data): array
     {
         $currentTenant = Filament::getTenant();
         if ($currentTenant) {
             // Automatically set the tenant_id for the new artwork being created
             $data['tenant_id'] = $currentTenant->id;
         }
         // Consider adding error handling or logging if $currentTenant is null

         return $data;
     }

    /**
     * Optional: Define the base Eloquent query for the resource.
     *
     * NOTE: With Filament's panel-level tenancy configured using ->tenant(),
     * this query is AUTOMATICALLY scoped to the current tenant based on the
     * `ownershipRelationship` defined in the Panel Provider ('tenant' in our case,
     * via the TenantAdmin model). You usually DO NOT need manual scoping here.
     */
    // public static function getEloquentQuery(): Builder
    // {
    //     // return parent::getEloquentQuery()->where('tenant_id', Filament::getTenant()?->id); // Applied automatically
    //     return parent::getEloquentQuery();
    // }
}