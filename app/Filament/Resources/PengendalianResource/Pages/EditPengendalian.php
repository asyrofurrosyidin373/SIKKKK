<?php

namespace App\Filament\Resources\PengendalianResource\Pages;

use App\Filament\Resources\PengendalianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengendalian extends EditRecord
{
    protected static string $resource = PengendalianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
