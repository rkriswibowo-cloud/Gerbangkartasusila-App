<?php

namespace App\Filament\Resources\CulinaryPlaceResource\Pages;

use App\Filament\Resources\CulinaryPlaceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulinaryPlace extends EditRecord
{
    protected static string $resource = CulinaryPlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
