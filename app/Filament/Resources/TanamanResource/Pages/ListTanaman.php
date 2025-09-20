<?php

namespace App\Filament\Resources\TanamanResource\Pages;

use App\Filament\Resources\TanamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTanaman extends ListRecords
{
    protected static string $resource = TanamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
