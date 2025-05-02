<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
//use App\Filament\Resources\CategoryResource\RelationManagers; // Keep if needed
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope; // Keep if needed
use Filament\Facades\Filament; // Import Filament Facade

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    // REMOVE or COMMENT OUT this line - Categories are now tenant-scoped!
    // public static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // tenant_id is handled automatically by Filament's tenancy
                // via the mutateFormDataBeforeCreate hook below.
                // DO NOT add a Select field for tenant_id here.
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Base query is automatically scoped by Filament's panel tenancy config
        return $table
            ->columns([
                // No need to show tenant ID/Name usually, user is in tenant context
                // Tables\Columns\TextColumn::make('tenant.name')->sortable(), // Optional
                Tables\Columns\TextColumn::make('id')
                   ->label('ID') // Assuming you still want the Category ID
                   ->searchable()
                   ->toggleable(isToggledHiddenByDefault: true), // Hide if not needed
                Tables\Columns\TextColumn::make('name')
                   ->searchable()
                   ->sortable(),
            ])
            ->filters([
                // Filters remain the same or add specific ones if needed
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
            // Relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    /**
     * Mutate form data before it is used to create the record.
     * Ensures the tenant_id is automatically set based on the current context.
     */
     public static function mutateFormDataBeforeCreate(array $data): array
     {
         // Get the current Tenant model instance that Filament has identified
         $currentTenant = Filament::getTenant();

         // Automatically set the tenant_id for the new category
         if ($currentTenant) {
             $data['tenant_id'] = $currentTenant->id;
         }
         // Optional: Add error handling if tenant is somehow not found

         return $data;
     }

     // You generally DON'T need this if panel tenancy is set up correctly.
     // Filament automatically applies the scope based on the relationship
     // defined in the Panel Provider ('tenant' on the User model).
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('tenant_id', Filament::getTenant()->id);
    // }
}