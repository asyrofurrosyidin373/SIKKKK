<?php

// 16. LaporanDeteksiResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\LaporanDeteksiResource\Pages;
use App\Models\LaporanDeteksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LaporanDeteksiResource extends Resource
{
    protected static ?string $model = LaporanDeteksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Deteksi';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi User & Tanaman')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('tanaman_id')
                            ->relationship('tanaman', 'nama_tanaman')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('varietas_id')
                            ->relationship('varietas', 'nama_varietas')
                            ->searchable()
                            ->preload(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Deteksi OPT')
                    ->schema([
                        Forms\Components\Select::make('org_pen_tan_id')
                            ->relationship('organisme', 'nama_opt')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Verified',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Laporan')
                    ->schema([
                        Forms\Components\TextInput::make('lokasi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('foto_path')
                            ->image()
                            ->directory('laporan-deteksi')
                            ->maxSize(5120)
                            ->label('Foto')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_path')
                    ->size(60)
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('User'),
                Tables\Columns\TextColumn::make('tanaman.nama_tanaman')
                    ->searchable()
                    ->label('Tanaman'),
                Tables\Columns\TextColumn::make('organisme.nama_opt')
                    ->searchable()
                    ->label('OPT Terdeteksi')
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'verified',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Laporan'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('tanaman_id')
                    ->relationship('tanaman', 'nama_tanaman')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('org_pen_tan_id')
                    ->relationship('organisme', 'nama_opt')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (LaporanDeteksi $record) => $record->update(['status' => 'verified']))
                    ->requiresConfirmation()
                    ->visible(fn (LaporanDeteksi $record) => $record->status === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (LaporanDeteksi $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation()
                    ->visible(fn (LaporanDeteksi $record) => $record->status === 'pending'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify_selected')
                        ->label('Verify Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'verified']))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('reject_selected')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'rejected']))
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanDeteksis::route('/'),
            'create' => Pages\CreateLaporanDeteksi::route('/create'),
            'edit' => Pages\EditLaporanDeteksi::route('/{record}/edit'),
        ];
    }
}