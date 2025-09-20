<?php

namespace App\Filament\Resources\TabKecamatanResource\Pages;

use App\Filament\Resources\TabKecamatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTabKecamatan extends EditRecord
{
    protected static string $resource = TabKecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
