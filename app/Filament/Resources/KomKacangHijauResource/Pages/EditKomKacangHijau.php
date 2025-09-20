<?php

namespace App\Filament\Resources\KomKacangHijauResource\Pages;

use App\Filament\Resources\KomKacangHijauResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomKacangHijau extends EditRecord
{
    protected static string $resource = KomKacangHijauResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
