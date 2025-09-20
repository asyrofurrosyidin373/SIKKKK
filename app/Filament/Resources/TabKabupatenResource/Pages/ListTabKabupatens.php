<?php

namespace App\Filament\Resources\TabKabupatenResource\Pages;

use App\Filament\Resources\TabKabupatenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTabKabupatens extends ListRecords
{
    protected static string $resource = TabKabupatenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
