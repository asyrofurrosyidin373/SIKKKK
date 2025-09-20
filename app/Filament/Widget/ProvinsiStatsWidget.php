<?php

namespace App\Filament\Widgets;

use App\Models\TabProvinsi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProvinsiStatsWidget extends BaseWidget
{
    protected static ?string $heading = 'Statistik Provinsi';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Provinsi', TabProvinsi::count())
                ->description('Jumlah provinsi yang terdaftar')
                ->descriptionIcon('heroicon-o-map-pin')
                ->color('success'),
            Stat::make('Total Kabupaten', TabProvinsi::withCount('kabupaten')->get()->sum('kabupaten_count'))
                ->description('Jumlah kabupaten keseluruhan')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('primary'),
        ];
    }
}