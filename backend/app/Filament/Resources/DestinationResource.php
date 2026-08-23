<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers;
use App\Models\Destination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                                Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('address')->required()->maxLength(255),
                Forms\Components\TextInput::make('latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->numeric(),
                Forms\Components\TextInput::make('ticket_price')->numeric()->default(0),
                Forms\Components\TextInput::make('opening_hours')->maxLength(255),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('destinations')->disk('public'),
                Forms\Components\Toggle::make('is_featured'),
                Forms\Components\Select::make('status')->options(['published' => 'Published', 'draft' => 'Draft'])->default('published')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                                Tables\Columns\ImageColumn::make('thumbnail')->disk('public'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('ticket_price')->money('IDR')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('status')->badge(),
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
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
