<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name'),
                    Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable(),
                    Select::make('unit_id')
                            ->label('Unit')
                            ->options(Unit::all()->pluck('name', 'id'))
                            ->searchable(),
                    TextInput::make('price')
                            ->numeric(),
                    Toggle::make('is_publish')
                            ->onIcon('heroicon-s-lightning-bolt')
                            ->offIcon('heroicon-s-user'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                            ->label('Title')
                                            ->sortable()
                                            ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                                            ->sortable()
                                            ->searchable(),
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\BooleanColumn::make('is_publish')
                                                ->label('Publish'),
                
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }    
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->withoutGlobalScopes();
}
}
