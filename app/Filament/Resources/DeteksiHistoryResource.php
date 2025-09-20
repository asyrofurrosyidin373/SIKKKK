<?php
// app/Filament/Resources/DeteksiHistoryResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\DeteksiHistoryResource\Pages;
use App\Models\DeteksiHistory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DeteksiHistoryResource extends Resource
{
    protected static ?string $model = DeteksiHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Riwayat Deteksi';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('detected_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('gejala_count')
                    ->label('Gejala')
                    ->getStateUsing(fn ($record) => count($record->gejala_ids))
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('results_count')
                    ->label('Hasil')
                    ->getStateUsing(fn ($record) => count($record->results))
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('top_result')
                    ->label('Hasil Teratas')
                    ->getStateUsing(function ($record) {
                        $results = collect($record->results);
                        if ($results->isEmpty()) return '-';
                        
                        $top = $results->sortByDesc('confidence_score')->first();
                        return $top['nama_penyakit'] ?? '-';
                    })
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn (Builder $query): Builder => $query->whereDate('detected_at', Carbon::today())),
                
                Filter::make('this_week')
                    ->label('Minggu Ini')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('detected_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])),
                
                Filter::make('this_month')
                    ->label('Bulan Ini')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('detected_at', Carbon::now()->month)
                                                                  ->whereYear('detected_at', Carbon::now()->year)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('detected_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeteksiHistories::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::today()->count();
    }
}