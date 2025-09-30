<?php

namespace App\Filament\Resources\TabKecamatanResource\Pages;

use App\Filament\Resources\TabKecamatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTabKecamatan extends ViewRecord
{
    protected static string $resource = TabKecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
