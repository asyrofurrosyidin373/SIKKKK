<?php

namespace App\Filament\Resources\GejalaResource\Pages;

use App\Filament\Resources\GejalaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGejala extends EditRecord
{
    protected static string $resource = GejalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
