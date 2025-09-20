<?php

namespace App\Filament\Resources\GejalaResource\Pages;

use App\Filament\Resources\GejalaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGejalas extends ListRecords
{
    protected static string $resource = GejalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
