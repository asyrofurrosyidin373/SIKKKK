<?php

// 2. TabKabupatenResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\TabKabupatenResource\Pages;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TabKabupatenResource extends Resource
{
    protected static ?string $model = TabKabupaten::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Geographic Data';
    protected static ?string $navigationLabel = 'Kabupaten';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
    ->label('Kode Kabupaten (Mendagri)')
    ->required()
    ->maxLength(4)
    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('tab_provinsi_id')
                    ->relationship('provinsi', 'nama_provinsi')
                    ->required()
                    ->searchable()
                    ->preload(),
                
                Forms\Components\TextInput::make('nama_kabupaten')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Section::make('Koordinat')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
    ->label('Kode Kabupaten')
    ->searchable()
    ->sortable(),

                Tables\Columns\TextColumn::make('provinsi.nama_provinsi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kabupaten')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric(8)
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric(8)
                    ->sortable(),
                Tables\Columns\TextColumn::make('kecamatan_count')
                    ->counts('kecamatan')
                    ->label('Jumlah Kecamatan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tab_provinsi_id')
                    ->relationship('provinsi', 'nama_provinsi')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListTabKabupatens::route('/'),
            'create' => Pages\CreateTabKabupaten::route('/create'),
            'edit' => Pages\EditTabKabupaten::route('/{record}/edit'),
        ];
    }
}