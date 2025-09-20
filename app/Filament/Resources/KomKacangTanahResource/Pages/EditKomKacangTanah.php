<?php

namespace App\Filament\Resources\KomKacangTanahResource\Pages;

use App\Filament\Resources\KomKacangTanahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomKacangTanah extends EditRecord
{
    protected static string $resource = KomKacangTanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
