<?php

namespace App\Filament\Resources\KomKacangTanahResource\Pages;

use App\Filament\Resources\KomKacangTanahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKomKacangTanahs extends ListRecords
{
    protected static string $resource = KomKacangTanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
