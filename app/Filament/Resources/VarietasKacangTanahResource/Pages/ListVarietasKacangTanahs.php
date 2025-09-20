<?php

namespace App\Filament\Resources\VarietasKacangTanahResource\Pages;

use App\Filament\Resources\VarietasKacangTanahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVarietasKacangTanahs extends ListRecords
{
    protected static string $resource = VarietasKacangTanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
