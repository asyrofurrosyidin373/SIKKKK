<?php

namespace App\Filament\Resources\VarietasKacangTanahResource\Pages;

use App\Filament\Resources\VarietasKacangTanahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVarietasKacangTanah extends EditRecord
{
    protected static string $resource = VarietasKacangTanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
