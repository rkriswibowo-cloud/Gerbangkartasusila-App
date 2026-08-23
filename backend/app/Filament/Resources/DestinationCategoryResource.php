<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationCategoryResource\Pages;
use App\Filament\Resources\DestinationCategoryResource\RelationManagers;
use App\Models\DestinationCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DestinationCategoryResource extends Resource
{
    protected static ?string $model = DestinationCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('icon')->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\TextColumn::make('icon'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinationCategories::route('/'),
            'create' => Pages\CreateDestinationCategory::route('/create'),
            'edit' => Pages\EditDestinationCategory::route('/{record}/edit'),
        ];
    }
}
