<?php

// 1. TabProvinsiResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\TabProvinsiResource\Pages;
use App\Models\TabProvinsi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TabProvinsiResource extends Resource
{
    protected static ?string $model = TabProvinsi::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Geographic Data';
    protected static ?string $navigationLabel = 'Provinsi';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
    ->label('Kode Provinsi (Mendagri)')
    ->required()
    ->maxLength(2)
    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('nama_provinsi')
                    ->required()
                    ->maxLength(64)
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Koordinat')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('Contoh: -6.2087634'),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('Contoh: 106.8456789'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
    ->label('Kode Provinsi')
    ->searchable()
    ->sortable(),

                Tables\Columns\TextColumn::make('nama_provinsi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric(8)
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric(8)
                    ->sortable(),
                Tables\Columns\TextColumn::make('kabupaten_count')
                    ->counts('kabupaten')
                    ->label('Jumlah Kabupaten'),
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
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabProvinsis::route('/'),
            'create' => Pages\CreateTabProvinsi::route('/create'),
            'edit' => Pages\EditTabProvinsi::route('/{record}/edit'),
        ];
    }
}
