<?php

namespace App\Filament\Resources\TanamanResource\Pages;

use App\Filament\Resources\TanamanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTanaman extends EditRecord
{
    protected static string $resource = TanamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
