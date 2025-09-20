<?php

namespace App\Filament\Resources\LaporanDeteksiResource\Pages;

use App\Filament\Resources\LaporanDeteksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanDeteksi extends EditRecord
{
    protected static string $resource = LaporanDeteksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
