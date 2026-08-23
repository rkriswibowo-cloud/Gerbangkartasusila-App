<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Filament\Resources\HotelResource\RelationManagers;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

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
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('hotels')->disk('public'),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')->disk('public'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
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
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
