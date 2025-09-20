<?php

// 4. TabBulanResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\TabBulanResource\Pages;
use App\Models\TabBulan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TabBulanResource extends Resource
{
    protected static ?string $model = TabBulan::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Data Bulan';
    protected static ?string $modelLabel = 'Bulan';
    protected static ?string $pluralModelLabel = 'Data Bulan';
    protected static ?string $navigationGroup = 'Geographic Data';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_bulan')
                    ->label('Nama Bulan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('angka_bulan')
                    ->label('Angka Bulan')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('angka_bulan')
                    ->label('No.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_bulan')
                    ->label('Nama Bulan')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('angka_bulan')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabBulans::route('/'),
            'create' => Pages\CreateTabBulan::route('/create'),
            'edit' => Pages\EditTabBulan::route('/{record}/edit'),
        ];
    }
}