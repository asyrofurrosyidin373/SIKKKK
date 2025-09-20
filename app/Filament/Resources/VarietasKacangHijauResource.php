<?php

// 11. VarietasKacangHijauResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\VarietasKacangHijauResource\Pages;
use App\Models\VarietasKacangHijau;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VarietasKacangHijauResource extends Resource
{
    protected static ?string $model = VarietasKacangHijau::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'OPT & Varietas';
    protected static ?string $navigationLabel = 'Varietas Kacang Hijau';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('nama_varietas')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('tahun')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        
                        Forms\Components\TextInput::make('sk')
                            ->label('SK')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('galur')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('asal')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Karakteristik Hasil')
                    ->schema([
                        Forms\Components\TextInput::make('potensi_hasil')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('ton/ha'),
                        
                        Forms\Components\TextInput::make('rata_hasil')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('ton/ha'),
                        
                        Forms\Components\TextInput::make('umur_berbunga')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('umur_masak')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Karakteristik Fisik')
                    ->schema([
                        Forms\Components\TextInput::make('tinggi_tanaman')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('warna_biji')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('bobot')
                            ->maxLength(255),
                    ])->columns(3),
                
                Forms\Components\Section::make('Kandungan Nutrisi')
                    ->schema([
                        Forms\Components\TextInput::make('kadar_lemak')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%'),
                        
                        Forms\Components\TextInput::make('kadar_protein')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Informasi Lainnya')
                    ->schema([
                        Forms\Components\TextInput::make('inventor')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('pengenal')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('org_pen_tan_id')
                            ->relationship('organisme', 'nama_opt')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\FileUpload::make('gambar')
                            ->image()
                            ->directory('varietas-images')
                            ->maxSize(2048),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('nama_varietas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('potensi_hasil')
                    ->numeric(2)
                    ->suffix(' ton/ha')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rata_hasil')
                    ->numeric(2)
                    ->suffix(' ton/ha')
                    ->sortable(),
                Tables\Columns\TextColumn::make('organisme.nama_opt')
                    ->label('Tahan OPT')
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('tahun')
                    ->form([
                        Forms\Components\TextInput::make('from_year')
                            ->numeric(),
                        Forms\Components\TextInput::make('to_year')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from_year'], fn($query, $year) => $query->where('tahun', '>=', $year))
                            ->when($data['to_year'], fn($query, $year) => $query->where('tahun', '<=', $year));
                    }),
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
            'index' => Pages\ListVarietasKacangHijaus::route('/'),
            'create' => Pages\CreateVarietasKacangHijau::route('/create'),
            'edit' => Pages\EditVarietasKacangHijau::route('/{record}/edit'),
        ];
    }
}