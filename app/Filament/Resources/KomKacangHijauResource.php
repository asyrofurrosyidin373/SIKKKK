<?php

// 7. KomKacangHijauResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\KomKacangHijauResource\Pages;
use App\Models\KomKacangHijau;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KomKacangHijauResource extends Resource
{
    protected static ?string $model = KomKacangHijau::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Komoditas';
    protected static ?string $navigationLabel = 'Kacang Hijau';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('provitas')
                    ->numeric()
                    ->step(0.01)
                    ->maxValue(99.99)
                    ->label('Provitas'),
                
                Forms\Components\TextInput::make('pot_peningkatan_judgement')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->label('Potensi Peningkatan (Judgement)'),
                
                Forms\Components\TextInput::make('nilai_potensi')
                    ->numeric()
                    ->step(0.01)
                    ->maxValue(99.99)
                    ->label('Nilai Potensi'),
                
                Forms\Components\Section::make('Relasi')
                    ->schema([
                        Forms\Components\Select::make('organisme')
                            ->relationship('organisme', 'nama_opt')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('varietas')
                            ->relationship('varietas', 'nama_varietas')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->limit(8),
                Tables\Columns\TextColumn::make('provitas')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('pot_peningkatan_judgement')
                    ->sortable()
                    ->label('Pot. Peningkatan'),
                Tables\Columns\TextColumn::make('nilai_potensi')
                    ->numeric(2)
                    ->sortable()
                    ->label('Nilai Potensi'),
                Tables\Columns\TextColumn::make('kecamatan_count')
                    ->counts('kecamatan')
                    ->label('Jumlah Kecamatan'),
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
            'index' => Pages\ListKomKacangHijaus::route('/'),
            'create' => Pages\CreateKomKacangHijau::route('/create'),
            'edit' => Pages\EditKomKacangHijau::route('/{record}/edit'),
        ];
    }
}
