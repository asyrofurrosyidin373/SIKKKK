<?php
// app/Filament/Resources/GejalaResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\GejalaResource\Pages;
use App\Models\Gejala;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;

class GejalaResource extends Resource
{
    protected static ?string $model = Gejala::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Gejala';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Gejala')
                    ->schema([
                        Forms\Components\TextInput::make('id_gejala')
                            ->label('ID Gejala')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('G001'),
                        
                        Forms\Components\Select::make('daerah')
                            ->label('Bagian Tanaman')
                            ->required()
                            ->options([
                                'Akar' => 'Akar',
                                'Batang' => 'Batang',
                                'Daun' => 'Daun',
                            ])
                            ->native(false),
                        
                        Forms\Components\TextInput::make('jenis_tanaman')
                            ->label('Jenis Tanaman')
                            ->default('Kedelai')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('gejala')
                            ->label('Deskripsi Gejala')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('id_gejala')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\BadgeColumn::make('daerah')
                    ->label('Bagian')
                    ->colors([
                        'success' => 'Akar',
                        'warning' => 'Batang',
                        'info' => 'Daun',
                    ])
                    ->icons([
                        'heroicon-m-arrow-down' => 'Akar',
                        'heroicon-m-bars-3' => 'Batang',
                        'heroicon-m-leaf' => 'Daun',
                    ]),
                
                Tables\Columns\TextColumn::make('gejala')
                    ->label('Gejala')
                    ->searchable()
                    ->limit(60)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 60 ? $state : null;
                    }),
                
                Tables\Columns\TextColumn::make('jenis_tanaman')
                    ->label('Tanaman')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('hama_penyakit_count')
                    ->label('Terkait')
                    ->counts('hamaPenyakit')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('daerah')
                    ->label('Bagian Tanaman')
                    ->options([
                        'Akar' => 'Akar',
                        'Batang' => 'Batang',
                        'Daun' => 'Daun',
                    ]),
                
                SelectFilter::make('jenis_tanaman')
                    ->label('Jenis Tanaman')
                    ->options([
                        'Kedelai' => 'Kedelai',
                    ]),
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
            ->defaultSort('daerah');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGejalas::route('/'),
            'create' => Pages\CreateGejala::route('/create'),
            'edit' => Pages\EditGejala::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}