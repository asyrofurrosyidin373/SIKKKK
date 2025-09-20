<?php

namespace App\Filament\Resources\KomKedelaiResource\Pages;

use App\Filament\Resources\KomKedelaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomKedelai extends EditRecord
{
    protected static string $resource = KomKedelaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
