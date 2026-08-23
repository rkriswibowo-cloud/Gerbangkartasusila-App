<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CulinaryPlaceResource\Pages;
use App\Filament\Resources\CulinaryPlaceResource\RelationManagers;
use App\Models\CulinaryPlace;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CulinaryPlaceResource extends Resource
{
    protected static ?string $model = CulinaryPlace::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('address')->required()->maxLength(255),
                Forms\Components\TextInput::make('latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->numeric(),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('culinary')->disk('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')->disk('public'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('address')->searchable(),
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
            'index' => Pages\ListCulinaryPlaces::route('/'),
            'create' => Pages\CreateCulinaryPlace::route('/create'),
            'edit' => Pages\EditCulinaryPlace::route('/{record}/edit'),
        ];
    }
}
