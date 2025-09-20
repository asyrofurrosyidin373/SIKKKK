<?php

namespace App\Filament\Resources\DeteksiHistoryResource\Pages;

use App\Filament\Resources\DeteksiHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeteksiHistories extends ListRecords
{
    protected static string $resource = DeteksiHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
