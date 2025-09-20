<?php

namespace App\Filament\Resources\InsektisidaResource\Pages;

use App\Filament\Resources\InsektisidaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInsektisidas extends ListRecords
{
    protected static string $resource = InsektisidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
