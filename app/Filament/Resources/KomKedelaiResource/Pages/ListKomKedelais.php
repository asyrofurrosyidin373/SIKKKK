<?php

namespace App\Filament\Resources\KomKedelaiResource\Pages;

use App\Filament\Resources\KomKedelaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKomKedelais extends ListRecords
{
    protected static string $resource = KomKedelaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
