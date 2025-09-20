<?php

namespace App\Filament\Resources\TabBulanResource\Pages;

use App\Filament\Resources\TabBulanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTabBulans extends ListRecords
{
    protected static string $resource = TabBulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
