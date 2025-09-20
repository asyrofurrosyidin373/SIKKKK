<?php

namespace App\Filament\Resources\TabBulanResource\Pages;

use App\Filament\Resources\TabBulanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTabBulan extends EditRecord
{
    protected static string $resource = TabBulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
