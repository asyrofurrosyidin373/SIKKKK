<?php

namespace App\Filament\Resources\VarietasKedelaiResource\Pages;

use App\Filament\Resources\VarietasKedelaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVarietasKedelai extends EditRecord
{
    protected static string $resource = VarietasKedelaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
