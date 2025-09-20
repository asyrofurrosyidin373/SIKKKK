<?php

namespace App\Filament\Resources\TabProvinsiResource\Pages;

use App\Filament\Resources\TabProvinsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTabProvinsis extends ListRecords
{
    protected static string $resource = TabProvinsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
