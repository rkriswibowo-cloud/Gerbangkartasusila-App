<?php

namespace App\Filament\Resources;

use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function canCreate(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('ticket_code')->disabled(),
            Forms\Components\TextInput::make('visitor_name')->disabled(),
            Forms\Components\TextInput::make('total_price')->disabled(),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Menunggu pembayaran', 'paid' => 'Lunas', 'cancelled' => 'Dibatalkan',
            ])->required()->helperText('Tandai lunas hanya setelah pembayaran diterima di lokasi.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('ticket_code')->searchable(),
            Tables\Columns\TextColumn::make('visitor_name')->searchable(),
            Tables\Columns\TextColumn::make('destination.name'),
            Tables\Columns\TextColumn::make('visit_date')->date()->sortable(),
            Tables\Columns\TextColumn::make('quantity'),
            Tables\Columns\TextColumn::make('total_price')->money('IDR'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => TicketResource\Pages\ManageTickets::route('/')];
    }
}
