<?php

namespace App\Filament\Resources\VarietasKacangHijauResource\Pages;

use App\Filament\Resources\VarietasKacangHijauResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVarietasKacangHijau extends EditRecord
{
    protected static string $resource = VarietasKacangHijauResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
