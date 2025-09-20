<?php

namespace App\Filament\Resources\HamaPenyakitResource\Pages;

use App\Filament\Resources\HamaPenyakitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHamaPenyakit extends EditRecord
{
    protected static string $resource = HamaPenyakitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
