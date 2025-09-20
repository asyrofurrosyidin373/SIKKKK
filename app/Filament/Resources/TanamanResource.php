<?php

// 12. TanamanResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\TanamanResource\Pages;
use App\Models\Tanaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TanamanResource extends Resource
{
    protected static ?string $model = Tanaman::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Diagnosis';
    protected static ?string $navigationLabel = 'Tanaman';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_tanaman')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_tanaman')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('laporan_deteksi_count')
                    ->counts('laporanDeteksi')
                    ->label('Jumlah Laporan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTanaman::route('/'),
            'create' => Pages\CreateTanaman::route('/create'),
            'edit' => Pages\EditTanaman::route('/{record}/edit'),
        ];
    }
}