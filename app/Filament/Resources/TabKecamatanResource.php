<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TabKecamatanResource\Pages;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabBulan;
use App\Models\KomKacangHijau;
use App\Models\KomKacangTanah;
use App\Models\KomKedelai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TabKecamatanResource extends Resource
{
    protected static ?string $model = TabKecamatan::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Data Kecamatan';
    protected static ?string $modelLabel = 'Kecamatan';
    protected static ?string $pluralModelLabel = 'Data Kecamatan';
    protected static ?string $navigationGroup = 'Geographic Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar Kecamatan')
                    ->description('Data dasar kecamatan')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('id')
                                    ->label('Kode Kecamatan')
                                    ->required()
                                    ->maxLength(6)
                                    ->placeholder('350101')
                                    ->helperText('Kode unik 6 digit'),

                                Select::make('tab_kabupaten_id')
                                    ->label('Kabupaten')
                                    ->required()
                                    ->searchable()
                                    ->options(function () {
                                        return TabKabupaten::pluck('nama_kabupaten', 'id');
                                    })
                                    ->placeholder('Pilih Kabupaten'),
                            ]),

                        TextInput::make('nama_kecamatan')
                            ->label('Nama Kecamatan')
                            ->required()
                            ->maxLength(64)
                            ->placeholder('Nama Kecamatan')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->placeholder('-7.123456'),

                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->placeholder('110.123456'),
                            ]),
                    ]),

                Section::make('🌱 Data Komoditas Unggulan')
                    ->description('Pilih komoditas unggulan untuk setiap jenis tanaman di kecamatan ini')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Select::make('kom_kedelai_id')
                                    ->label('🫘 Komoditas Kedelai Unggulan')
                                    ->searchable()
                                    ->placeholder('Pilih varietas kedelai unggulan...')
                                    ->options(function () {
                                        return KomKedelai::pluck('provitas', 'id');
                                    })
                                    ->helperText('Pilih varietas kedelai yang paling cocok untuk wilayah ini')
                                    ->columnSpanFull(),

                                Select::make('kom_kacang_tanah_id')
                                    ->label('🥜 Komoditas Kacang Tanah Unggulan')
                                    ->searchable()
                                    ->placeholder('Pilih varietas kacang tanah unggulan...')
                                    ->options(function () {
                                        return KomKacangTanah::pluck('provitas', 'id');
                                    })
                                    ->helperText('Pilih varietas kacang tanah yang paling cocok untuk wilayah ini')
                                    ->columnSpanFull(),

                                Select::make('kom_kacang_hijau_id')
                                    ->label('🫛 Komoditas Kacang Hijau Unggulan')
                                    ->searchable()
                                    ->placeholder('Pilih varietas kacang hijau unggulan...')
                                    ->options(function () {
                                        return KomKacangHijau::pluck('provitas', 'id');
                                    })
                                    ->helperText('Pilih varietas kacang hijau yang paling cocok untuk wilayah ini')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('🌍 Data Kondisi Tanah')
                    ->description('Informasi kimia dan fisik tanah')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('ip_lahan')
                                    ->label('IP Lahan (%)')
                                    ->numeric()
                                    ->placeholder('0.00'),

                                TextInput::make('kdr_p')
                                    ->label('Kadar P (ppm)')
                                    ->numeric()
                                    ->placeholder('0.00'),

                                TextInput::make('kdr_c')
                                    ->label('Kadar C (%)')
                                    ->numeric()
                                    ->placeholder('0.00'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('kdr_k')
                                    ->label('Kadar K (me/100g)')
                                    ->numeric()
                                    ->placeholder('0.00'),

                                TextInput::make('ktk')
                                    ->label('KTK (me/100g)')
                                    ->numeric()
                                    ->placeholder('0.00'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(true),

                Section::make('🌧️ Pola Cuaca')
                    ->description('Informasi curah hujan bulanan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('bulan_hujan')
                                    ->label('Bulan Hujan')
                                    ->multiple()
                                    ->searchable()
                                    ->options(function () {
                                        return TabBulan::orderBy('angka_bulan')
                                            ->pluck('nama_bulan', 'id');
                                    })
                                    ->placeholder('Pilih bulan-bulan dengan curah hujan tinggi'),

                                Select::make('bulan_kering')
                                    ->label('Bulan Kering')
                                    ->multiple()
                                    ->searchable()
                                    ->options(function () {
                                        return TabBulan::orderBy('angka_bulan')
                                            ->pluck('nama_bulan', 'id');
                                    })
                                    ->placeholder('Pilih bulan-bulan dengan curah hujan rendah'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(true),

                Section::make('📅 Rekomendasi Waktu Tanam')
                    ->description('Waktu tanam optimal untuk setiap komoditas')
                    ->schema([
                        Select::make('rekomendasi_waktu_tanam_kedelai')
                            ->label('⏰ Waktu Tanam Kedelai')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                return TabBulan::orderBy('angka_bulan')
                                    ->pluck('nama_bulan', 'id');
                            })
                            ->placeholder('Pilih bulan-bulan untuk menanam kedelai'),

                        Select::make('rekomendasi_waktu_tanam_kacang_tanah')
                            ->label('⏰ Waktu Tanam Kacang Tanah')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                return TabBulan::orderBy('angka_bulan')
                                    ->pluck('nama_bulan', 'id');
                            })
                            ->placeholder('Pilih bulan-bulan untuk menanam kacang tanah'),

                        Select::make('rekomendasi_waktu_tanam_kacang_hijau')
                            ->label('⏰ Waktu Tanam Kacang Hijau')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                return TabBulan::orderBy('angka_bulan')
                                    ->pluck('nama_bulan', 'id');
                            })
                            ->placeholder('Pilih bulan-bulan untuk menanam kacang hijau'),
                    ])
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('kabupaten.nama_kabupaten')
                    ->label('Kabupaten')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('komoditasKedelai.provitas')
                    ->label('Kedelai')
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    })
                    ->toggleable(),

                TextColumn::make('komoditasKacangTanah.provitas')
                    ->label('Kacang Tanah')
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    })
                    ->toggleable(),

                TextColumn::make('komoditasKacangHijau.provitas')
                    ->label('Kacang Hijau')
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    })
                    ->toggleable(),

                TextColumn::make('ip_lahan')
                    ->label('IP Lahan')
                    ->suffix('%')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tab_kabupaten_id')
                    ->label('Kabupaten')
                    ->relationship('kabupaten', 'nama_kabupaten'),

                Tables\Filters\SelectFilter::make('kom_kedelai_id')
                    ->label('Komoditas Kedelai')
                    ->relationship('komoditasKedelai', 'provitas'),

                Tables\Filters\SelectFilter::make('kom_kacang_tanah_id')
                    ->label('Komoditas Kacang Tanah')
                    ->relationship('komoditasKacangTanah', 'provitas'),

                Tables\Filters\SelectFilter::make('kom_kacang_hijau_id')
                    ->label('Komoditas Kacang Hijau')
                    ->relationship('komoditasKacangHijau', 'provitas'),
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
            ->defaultSort('nama_kecamatan');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabKecamatans::route('/'),
            'create' => Pages\CreateTabKecamatan::route('/create'),
            'edit' => Pages\EditTabKecamatan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}