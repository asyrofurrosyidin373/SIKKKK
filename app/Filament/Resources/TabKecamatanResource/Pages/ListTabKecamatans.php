<?php

namespace App\Filament\Resources\TabKecamatanResource\Pages;

use App\Filament\Resources\TabKecamatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTabKecamatans extends ListRecords
{
    protected static string $resource = TabKecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
