<?php

// 14. PengendalianResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\PengendalianResource\Pages;
use App\Models\Pengendalian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengendalianResource extends Resource
{
    protected static ?string $model = Pengendalian::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Diagnosis';
    protected static ?string $navigationLabel = 'Pengendalian';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis')
                    ->options([
                        'Kultur teknis' => 'Kultur teknis',
                        'Mekanis' => 'Mekanis',
                        'Kimiawi' => 'Kimiawi',
                        'Hayati' => 'Hayati',
                    ])
                    ->required(),
                
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Relasi')
                    ->schema([
                        Forms\Components\Select::make('organisme')
                            ->relationship('organisme', 'nama_opt')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('insektisida')
                            ->relationship('insektisida', 'nama')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get) => $get('jenis') === 'Kimiawi'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('jenis')
                    ->colors([
                        'success' => 'Kultur teknis',
                        'primary' => 'Mekanis',
                        'danger' => 'Kimiawi',
                        'warning' => 'Hayati',
                    ]),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(60)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organisme_count')
                    ->counts('organisme')
                    ->label('Jumlah OPT'),
                Tables\Columns\TextColumn::make('insektisida_count')
                    ->counts('insektisida')
                    ->label('Jumlah Insektisida'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'Kultur teknis' => 'Kultur teknis',
                        'Mekanis' => 'Mekanis',
                        'Kimiawi' => 'Kimiawi',
                        'Hayati' => 'Hayati',
                    ]),
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
            'index' => Pages\ListPengendalians::route('/'),
            'create' => Pages\CreatePengendalian::route('/create'),
            'edit' => Pages\EditPengendalian::route('/{record}/edit'),
        ];
    }
}
