<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProvinsiStatsWidget;
use App\Filament\Widgets\ProvinsiChartWidget; // Jika kamu buat chart
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            ProvinsiStatsWidget::class,
            ProvinsiChartWidget::class, // Tambahkan jika ada
        ];
    }
}