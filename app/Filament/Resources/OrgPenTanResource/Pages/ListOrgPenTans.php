<?php

namespace App\Filament\Resources\OrgPenTanResource\Pages;

use App\Filament\Resources\OrgPenTanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrgPenTans extends ListRecords
{
    protected static string $resource = OrgPenTanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
