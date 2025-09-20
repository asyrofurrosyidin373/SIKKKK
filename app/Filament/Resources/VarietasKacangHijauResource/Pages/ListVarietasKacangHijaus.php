<?php

namespace App\Filament\Resources\VarietasKacangHijauResource\Pages;

use App\Filament\Resources\VarietasKacangHijauResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVarietasKacangHijaus extends ListRecords
{
    protected static string $resource = VarietasKacangHijauResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
