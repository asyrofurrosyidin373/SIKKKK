<?php

namespace App\Filament\Resources\HamaPenyakitResource\Pages;

use App\Filament\Resources\HamaPenyakitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHamaPenyakits extends ListRecords
{
    protected static string $resource = HamaPenyakitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
