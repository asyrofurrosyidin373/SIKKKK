<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TabKecamatanResource\Pages;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use App\Models\HamaPenyakit;
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
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Grid as InfoGrid;

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
                                    ->options(TabKabupaten::with('provinsi')->get()->pluck('nama_kabupaten', 'id'))
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('nama_kecamatan')
                                    ->label('Nama Kecamatan')
                                    ->required()
                                    ->maxLength(64)
                                    ->placeholder('Contoh: Donomulyo')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('OPT (Hama)')
                    ->description('Pilih OPT jenis Hama yang relevan dengan kecamatan ini')
                    ->icon('heroicon-o-bug-ant')
                    ->schema([
                        Select::make('opt_id')
                            ->label('OPT Hama')
                            ->multiple()
                            ->options(function () {
                                return HamaPenyakit::where('terjangkit', 'Hama')
                                    ->orderBy('nama_penyakit')
                                    ->pluck('nama_penyakit', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->helperText('Simpan sebagai daftar ID OPT (field JSON)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Koordinat & Lokasi')
                    ->description('Data geografis kecamatan')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.000001)
                                    ->placeholder('-8.2435')
                                    ->helperText('Koordinat lintang'),

                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.000001)
                                    ->placeholder('112.4419')
                                    ->helperText('Koordinat bujur'),
                            ]),
                    ]),

                Section::make('Data Tanah')
                    ->description('Karakteristik tanah kecamatan')
                    ->icon('heroicon-o-beaker')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('ip_lahan')
                                    ->label('IP Lahan')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('3.2')
                                    ->helperText('Indeks Produktivitas Lahan'),

                                TextInput::make('kdr_p')
                                    ->label('Kadar P')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('2.5')
                                    ->helperText('Kadar Fosfor'),

                                TextInput::make('kdr_c')
                                    ->label('Kadar C')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('2.1')
                                    ->helperText('Kadar Karbon'),

                                TextInput::make('kdr_k')
                                    ->label('Kadar K')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('1.8')
                                    ->helperText('Kadar Kalium'),

                                TextInput::make('ktk')
                                    ->label('KTK')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('2.9')
                                    ->helperText('Kapasitas Tukar Kation'),
                            ]),
                    ]),

                Section::make('Data Komoditas')
                    ->description('Informasi komoditas yang dibudidayakan')
                    ->icon('heroicon-o-bars-3')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('jenis_komoditas')
                                    ->label('Jenis Komoditas')
                                    ->options([
                                        'kedelai' => 'Kedelai',
                                        'kacang_tanah' => 'Kacang Tanah',
                                        'kacang_hijau' => 'Kacang Hijau',
                                    ])
                                    ->required()
                                    ->searchable()
                                    ->reactive(),

                                Select::make('varietas_id')
                                    ->label('Varietas')
                                    ->multiple()
                                    ->options(function (callable $get) {
                                        $jenis = $get('jenis_komoditas');
                                        switch ($jenis) {
                                            case 'kedelai':
                                                return VarietasKedelai::pluck('nama_varietas', 'id');
                                            case 'kacang_tanah':
                                                return VarietasKacangTanah::pluck('nama_varietas', 'id');
                                            case 'kacang_hijau':
                                                return VarietasKacangHijau::pluck('nama_varietas', 'id');
                                            default:
                                                return [];
                                        }
                                    })
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('provitas')
                                    ->label('Provitas')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('2.5')
                                    ->helperText('Nilai provitas'),

                                TextInput::make('nilai_potensi')
                                    ->label('Nilai Potensi')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('3.2')
                                    ->helperText('Nilai potensi peningkatan'),

                                TextInput::make('pot_peningkatan_judgement')
                                    ->label('Judgement Peningkatan')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->placeholder('7')
                                    ->helperText('Skala 1-10'),
                            ]),
                    ]),

                Section::make('Data Produksi')
                    ->description('Data produksi komoditas')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('luas_tanam')
                                    ->label('Luas Tanam (ha)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('100.50')
                                    ->helperText('Luas tanam dalam hektar'),

                                TextInput::make('produktivitas')
                                    ->label('Produktivitas (ton/ha)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('2.5')
                                    ->helperText('Produktivitas per hektar'),

                                TextInput::make('total_produksi')
                                    ->label('Total Produksi (ton)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder('250.75')
                                    ->helperText('Total produksi dalam ton'),
                            ]),
                    ]),

                Section::make('Rekomendasi Waktu Tanam')
                    ->description('Waktu tanam yang direkomendasikan untuk setiap komoditas')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TagsInput::make('rekomendasi_waktu_tanam_kedelai')
                            ->label('Waktu Tanam Kedelai')
                            ->placeholder('Contoh: Maret, April')
                            ->helperText('Bulan-bulan yang direkomendasikan untuk menanam kedelai'),

                        TagsInput::make('rekomendasi_waktu_tanam_kacang_tanah')
                            ->label('Waktu Tanam Kacang Tanah')
                            ->placeholder('Contoh: April, Mei')
                            ->helperText('Bulan-bulan yang direkomendasikan untuk menanam kacang tanah'),

                        TagsInput::make('rekomendasi_waktu_tanam_kacang_hijau')
                            ->label('Waktu Tanam Kacang Hijau')
                            ->placeholder('Contoh: Mei, Juni')
                            ->helperText('Bulan-bulan yang direkomendasikan untuk menanam kacang hijau'),
                    ]),

                Section::make('Data Iklim')
                    ->description('Pola iklim di kecamatan')
                    ->icon('heroicon-o-cloud')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TagsInput::make('bulan_hujan')
                                    ->label('Bulan Hujan')
                                    ->placeholder('Contoh: Januari, Februari, Maret')
                                    ->helperText('Bulan-bulan dengan curah hujan tinggi'),

                                TagsInput::make('bulan_kering')
                                    ->label('Bulan Kering')
                                    ->placeholder('Contoh: Juli, Agustus, September')
                                    ->helperText('Bulan-bulan dengan curah hujan rendah'),
                            ]),
                    ]),
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
                    ->label('Nama Kecamatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kabupaten.nama_kabupaten')
                    ->label('Kabupaten')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kabupaten.provinsi.nama_provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('formatted_coordinates')
                    ->label('Koordinat')
                    ->getStateUsing(fn ($record) => $record->formatted_coordinates)
                    ->color('gray'),

                TextColumn::make('jenis_komoditas')
                    ->label('Jenis Komoditas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'kedelai' => 'success',
                        'kacang_tanah' => 'warning',
                        'kacang_hijau' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kedelai' => 'Kedelai',
                        'kacang_tanah' => 'Kacang Tanah',
                        'kacang_hijau' => 'Kacang Hijau',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('nama_varietas')
                    ->label('Varietas')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('opt_list')
                    ->label('OPT (Hama)')
                    ->limit(40)
                    ->getStateUsing(function ($record) {
                        try {
                            $ids = $record->opt_id;
                            if (empty($ids)) return '';
                            if (!is_array($ids)) {
                                $ids = json_decode($ids, true) ?: [];
                            }
                            if (empty($ids)) return '';
                            return HamaPenyakit::whereIn('id', $ids)->pluck('nama_penyakit')->join(', ');
                        } catch (\Throwable $e) {
                            return '';
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('luas_tanam')
                    ->label('Luas Tanam (ha)')
                    ->numeric(2)
                    ->sortable(),

                TextColumn::make('total_produksi')
                    ->label('Produksi (ton)')
                    ->numeric(2)
                    ->sortable(),

                TextColumn::make('ip_lahan')
                    ->label('IP Lahan')
                    ->numeric(2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tab_kabupaten_id')
                    ->label('Kabupaten')
                    ->options(TabKabupaten::all()->pluck('nama_kabupaten', 'id'))
                    ->searchable(),

                SelectFilter::make('jenis_komoditas')
                    ->label('Jenis Komoditas')
                    ->options([
                        'kedelai' => 'Kedelai',
                        'kacang_tanah' => 'Kacang Tanah',
                        'kacang_hijau' => 'Kacang Hijau',
                    ]),

                SelectFilter::make('has_coordinates')
                    ->label('Koordinat')
                    ->options([
                        '1' => 'Ada Koordinat',
                        '0' => 'Tanpa Koordinat',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === '1') {
                            return $query->whereNotNull('latitude')->whereNotNull('longitude');
                        } elseif ($data['value'] === '0') {
                            return $query->where(function ($q) {
                                $q->whereNull('latitude')->orWhereNull('longitude');
                            });
                        }
                    }),

                SelectFilter::make('has_production')
                    ->label('Data Produksi')
                    ->options([
                        '1' => 'Ada Data Produksi',
                        '0' => 'Tanpa Data Produksi',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === '1') {
                            return $query->whereNotNull('luas_tanam')
                                        ->whereNotNull('produktivitas')
                                        ->whereNotNull('total_produksi');
                        } elseif ($data['value'] === '0') {
                            return $query->where(function ($q) {
                                $q->whereNull('luas_tanam')
                                  ->orWhereNull('produktivitas')
                                  ->orWhereNull('total_produksi');
                            });
                        }
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nama_kecamatan');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Informasi Dasar')
                    ->schema([
                        InfoGrid::make(2)
                            ->schema([
                                TextEntry::make('id')
                                    ->label('Kode Kecamatan'),
                                TextEntry::make('nama_kecamatan')
                                    ->label('Nama Kecamatan')
                                    ->weight('bold'),
                                TextEntry::make('kabupaten.nama_kabupaten')
                                    ->label('Kabupaten'),
                                TextEntry::make('kabupaten.provinsi.nama_provinsi')
                                    ->label('Provinsi'),
                            ]),
                    ]),

                InfoSection::make('Koordinat & Lokasi')
                    ->schema([
                        InfoGrid::make(2)
                            ->schema([
                                TextEntry::make('latitude')
                                    ->label('Latitude')
                                    ->numeric(6),
                                TextEntry::make('longitude')
                                    ->label('Longitude')
                                    ->numeric(6),
                            ]),
                    ]),

                InfoSection::make('Data Tanah')
                    ->schema([
                        InfoGrid::make(3)
                            ->schema([
                                TextEntry::make('ip_lahan')
                                    ->label('IP Lahan')
                                    ->numeric(2),
                                TextEntry::make('kdr_p')
                                    ->label('Kadar P')
                                    ->numeric(2),
                                TextEntry::make('kdr_c')
                                    ->label('Kadar C')
                                    ->numeric(2),
                                TextEntry::make('kdr_k')
                                    ->label('Kadar K')
                                    ->numeric(2),
                                TextEntry::make('ktk')
                                    ->label('KTK')
                                    ->numeric(2),
                            ]),
                    ]),

                InfoSection::make('Data Komoditas')
                    ->schema([
                        InfoGrid::make(2)
                            ->schema([
                                TextEntry::make('jenis_komoditas')
                                    ->label('Jenis Komoditas')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'kedelai' => 'success',
                                        'kacang_tanah' => 'warning',
                                        'kacang_hijau' => 'info',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'kedelai' => 'Kedelai',
                                        'kacang_tanah' => 'Kacang Tanah',
                                        'kacang_hijau' => 'Kacang Hijau',
                                        default => $state,
                                    }),
                                TextEntry::make('nama_varietas')
                                    ->label('Varietas'),
                            ]),
                        InfoGrid::make(3)
                            ->schema([
                                TextEntry::make('provitas')
                                    ->label('Provitas')
                                    ->numeric(2),
                                TextEntry::make('nilai_potensi')
                                    ->label('Nilai Potensi')
                                    ->numeric(2),
                                TextEntry::make('pot_peningkatan_judgement')
                                    ->label('Judgement Peningkatan'),
                            ]),
                    ]),

                InfoSection::make('Data Produksi')
                    ->schema([
                        InfoGrid::make(3)
                            ->schema([
                                TextEntry::make('luas_tanam')
                                    ->label('Luas Tanam (ha)')
                                    ->numeric(2),
                                TextEntry::make('produktivitas')
                                    ->label('Produktivitas (ton/ha)')
                                    ->numeric(2),
                                TextEntry::make('total_produksi')
                                    ->label('Total Produksi (ton)')
                                    ->numeric(2),
                            ]),
                    ]),

                InfoSection::make('Rekomendasi Waktu Tanam')
                    ->schema([
                        TextEntry::make('rekomendasi_waktu_tanam_kedelai')
                            ->label('Kedelai')
                            ->badge()
                            ->getStateUsing(fn ($record) => $record->rekomendasi_waktu_tanam_kedelai ?? []),
                        TextEntry::make('rekomendasi_waktu_tanam_kacang_tanah')
                            ->label('Kacang Tanah')
                            ->badge()
                            ->getStateUsing(fn ($record) => $record->rekomendasi_waktu_tanam_kacang_tanah ?? []),
                        TextEntry::make('rekomendasi_waktu_tanam_kacang_hijau')
                            ->label('Kacang Hijau')
                            ->badge()
                            ->getStateUsing(fn ($record) => $record->rekomendasi_waktu_tanam_kacang_hijau ?? []),
                    ]),

                InfoSection::make('Data Iklim')
                    ->schema([
                        InfoGrid::make(2)
                            ->schema([
                                TextEntry::make('bulan_hujan')
                                    ->label('Bulan Hujan')
                                    ->badge()
                                    ->color('blue')
                                    ->getStateUsing(fn ($record) => $record->bulan_hujan ?? []),
                                TextEntry::make('bulan_kering')
                                    ->label('Bulan Kering')
                                    ->badge()
                                    ->color('orange')
                                    ->getStateUsing(fn ($record) => $record->bulan_kering ?? []),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabKecamatans::route('/'),
            'create' => Pages\CreateTabKecamatan::route('/create'),
            'view' => Pages\ViewTabKecamatan::route('/{record}'),
            'edit' => Pages\EditTabKecamatan::route('/{record}/edit'),
        ];
    }
}
