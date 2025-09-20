<?php
// app/Filament/Resources/InsektisidaResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\InsektisidaResource\Pages;
use App\Models\Insektisida;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class InsektisidaResource extends Resource
{
    protected static ?string $model = Insektisida::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationLabel = 'Insektisida';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Insektisida')
                    ->schema([
                        Forms\Components\TextInput::make('id_insektisida')
                            ->label('ID Insektisida')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('IN001'),
                        
                        Forms\Components\TextInput::make('nama_insektisida')
                            ->label('Nama Insektisida')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('bahan_aktif')
                            ->label('Bahan Aktif')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('hama_sasaran')
                            ->label('Hama Sasaran')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('dosis')
                            ->label('Dosis Aplikasi')
                            ->rows(3),
                        
                        Forms\Components\Textarea::make('cara_aplikasi')
                            ->label('Cara Aplikasi')
                            ->rows(3),
                    ])
                    ->columns(2),

                Section::make('Hama & Penyakit Terkait')
                    ->schema([
                        Forms\Components\CheckboxList::make('hamaPenyakit')
                            ->relationship('hamaPenyakit', 'nama_penyakit')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->terjangkit}: {$record->nama_penyakit}")
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_insektisida')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('nama_insektisida')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('bahan_aktif')
                    ->label('Bahan Aktif')
                    ->searchable()
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('hama_sasaran')
                    ->label('Sasaran')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                
                Tables\Columns\TextColumn::make('hama_penyakit_count')
                    ->label('Terkait')
                    ->counts('hamaPenyakit')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\IconColumn::make('has_dosis')
                    ->label('Dosis')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->dosis)),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->defaultSort('nama_insektisida');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInsektisidas::route('/'),
            'create' => Pages\CreateInsektisida::route('/create'),
            'edit' => Pages\EditInsektisida::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}