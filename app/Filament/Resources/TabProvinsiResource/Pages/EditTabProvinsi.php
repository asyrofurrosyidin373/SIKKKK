<?php

namespace App\Filament\Resources\TabProvinsiResource\Pages;

use App\Filament\Resources\TabProvinsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTabProvinsi extends EditRecord
{
    protected static string $resource = TabProvinsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
