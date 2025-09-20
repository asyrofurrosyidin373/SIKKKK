<?php
// app/Filament/Resources/HamaPenyakitResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\HamaPenyakitResource\Pages;
use App\Models\HamaPenyakit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class HamaPenyakitResource extends Resource
{
    protected static ?string $model = HamaPenyakit::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';

    protected static ?string $navigationLabel = 'Hama & Penyakit';

    protected static ?string $modelLabel = 'Hama & Penyakit';

    protected static ?string $pluralModelLabel = 'Hama & Penyakit';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('id_penyakit')
                            ->label('ID Penyakit')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('PH001'),
                        
                        Forms\Components\TextInput::make('nama_penyakit')
                            ->label('Nama Hama/Penyakit')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('terjangkit')
                            ->label('Jenis')
                            ->required()
                            ->options([
                                'Hama' => 'Hama',
                                'Penyakit' => 'Penyakit',
                            ])
                            ->native(false),
                        
                        Forms\Components\TextInput::make('jenis_tanaman')
                            ->label('Jenis Tanaman')
                            ->default('Kedelai')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('gambar')
                            ->label('Gambar')
                            ->image()
                            ->directory('hama-penyakit')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Metode Pengendalian')
                    ->schema([
                        Forms\Components\Textarea::make('kultur_teknis')
                            ->label('Kultur Teknis')
                            ->rows(4),
                        
                        Forms\Components\Textarea::make('fisik_mekanis')
                            ->label('Fisik Mekanis')
                            ->rows(4),
                        
                        Forms\Components\Textarea::make('hayati')
                            ->label('Hayati')
                            ->rows(4),
                        
                        Forms\Components\Textarea::make('kimiawi')
                            ->label('Kimiawi')
                            ->rows(4),
                    ])
                    ->columns(2),

                Section::make('Gejala')
                    ->schema([
                        Forms\Components\CheckboxList::make('gejala')
                            ->relationship('gejala', 'gejala')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->daerah}: {$record->gejala}")
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2),
                    ]),

                Section::make('Insektisida')
                    ->schema([
                        Forms\Components\CheckboxList::make('insektisida')
                            ->relationship('insektisida', 'nama_insektisida')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_insektisida} ({$record->bahan_aktif})")
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
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular()
                    ->defaultImageUrl('/images/placeholder.png'),
                
                Tables\Columns\TextColumn::make('id_penyakit')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('nama_penyakit')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                Tables\Columns\BadgeColumn::make('terjangkit')
                    ->label('Jenis')
                    ->colors([
                        'danger' => 'Hama',
                        'warning' => 'Penyakit',
                    ])
                    ->icons([
                        'heroicon-m-bug-ant' => 'Hama',
                        'heroicon-m-virus' => 'Penyakit',
                    ]),
                
                Tables\Columns\TextColumn::make('jenis_tanaman')
                    ->label('Tanaman')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('gejala_count')
                    ->label('Gejala')
                    ->counts('gejala')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\IconColumn::make('has_control')
                    ->label('Pengendalian')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->hasControlMethods()),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('terjangkit')
                    ->label('Jenis')
                    ->options([
                        'Hama' => 'Hama',
                        'Penyakit' => 'Penyakit',
                    ]),
                
                SelectFilter::make('jenis_tanaman')
                    ->label('Tanaman')
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHamaPenyakits::route('/'),
            'create' => Pages\CreateHamaPenyakit::route('/create'),
            'edit' => Pages\EditHamaPenyakit::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}