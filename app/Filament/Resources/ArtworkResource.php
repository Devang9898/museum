<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtworkResource\Pages;
use App\Models\Artwork;
use App\Models\Category;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ViewAction;

class ArtworkResource extends Resource
{
    protected static ?string $model = Artwork::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $tenant = Filament::getTenant();
                            if ($tenant) {
                                $query->where('tenant_id', $tenant->id);
                            } else {
                                $query->whereRaw('1 = 0');
                            }
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\FileUpload::make('image')
                    ->label('Artwork Image')
                    ->image()
                    ->directory('artworks')
                    ->visibility('public')
                    ->nullable(),

                // New fields
                Forms\Components\TextInput::make('length')
                    ->label('Length (cm/in)')
                    ->numeric()
                    ->step(0.01)
                    ->nullable(),

                Forms\Components\TextInput::make('breadth')
                    ->label('Breadth (cm/in)')
                    ->numeric()
                    ->step(0.01)
                    ->nullable(),

                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->prefix('₹') // or '$' depending on your locale
                    ->numeric()
                    ->step(0.01)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image')
                    ->disk('public'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->placeholder('N/A'),

                // New columns
                Tables\Columns\TextColumn::make('length')
                    ->label('Length')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('breadth')
                    ->label('Breadth')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('INR', locale: 'en_IN') // Customize currency if needed
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship(
                        'category',
                        'name',
                        modifyQueryUsing: fn (Builder $query) =>
                            $query->where('tenant_id', Filament::getTenant()?->id)
                    )
                    ->searchable()
                    ->preload()
                    ->label('Filter by Category'),
            ])
            ->actions([
                ViewAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtworks::route('/'),
            'create' => Pages\CreateArtwork::route('/create'),
            'edit' => Pages\EditArtwork::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $currentTenant = Filament::getTenant();
        if ($currentTenant) {
            $data['tenant_id'] = $currentTenant->id;
        }

        return $data;
    }
}
