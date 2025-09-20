<?php

namespace App\Filament\Widgets;

use App\Models\TabProvinsi;
use Filament\Widgets\BarChartWidget;

class ProvinsiChartWidget extends BarChartWidget
{
    protected static ?string $heading = 'Jumlah Kabupaten per Provinsi';

    protected function getData(): array
    {
        $provinsis = TabProvinsi::withCount('kabupaten')
            ->orderBy('kabupaten_count', 'desc')
            ->limit(10) // Batasi ke 10 provinsi teratas
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kabupaten',
                    'data' => $provinsis->pluck('kabupaten_count'),
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $provinsis->pluck('nama_provinsi'),
        ];
    }
}