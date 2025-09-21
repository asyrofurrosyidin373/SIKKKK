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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RangeSlider;
use Illuminate\Database\Eloquent\Builder;

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
                        
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan dari sistem deteksi'),
                        
                        TextInput::make('frequency')
                            ->label('Frekuensi (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(50)
                            ->helperText('Seberapa sering gejala ini muncul'),
                        
                        TextInput::make('severity_score')
                            ->label('Tingkat Keparahan (1-10)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->step(0.1)
                            ->default(5)
                            ->helperText('1 = Ringan, 10 = Sangat Parah'),
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
                
                Tables\Columns\TextColumn::make('frequency')
                    ->label('Frekuensi')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'info',
                        $state >= 40 => 'warning',
                        default => 'danger'
                    })
                    ->suffix('%')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('severity_score')
                    ->label('Keparahan')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 8 => 'danger',
                        $state >= 6 => 'warning',
                        $state >= 4 => 'info',
                        default => 'success'
                    })
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                
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
                        'Kacang Tanah' => 'Kacang Tanah',
                        'Kacang Hijau' => 'Kacang Hijau',
                    ]),
                
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
                
                Filter::make('severity_range')
                    ->form([
                        RangeSlider::make('severity_score')
                            ->label('Rentang Keparahan')
                            ->min(1)
                            ->max(10)
                            ->step(0.1)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['severity_score'],
                                fn (Builder $query, $severity): Builder => $query->whereBetween('severity_score', $severity),
                            );
                    }),
                
                Filter::make('frequency_range')
                    ->form([
                        RangeSlider::make('frequency')
                            ->label('Rentang Frekuensi')
                            ->min(0)
                            ->max(100)
                            ->step(5)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['frequency'],
                                fn (Builder $query, $frequency): Builder => $query->whereBetween('frequency', $frequency),
                            );
                    }),
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
            ->defaultSort('severity_score', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds
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