<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Illuminate\Database\Eloquent\Model;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Log Kayıtları';
    protected static ?string $pluralModelLabel = 'Log Kayıtları';
    protected static ?int $navigationSort = 1;

    protected static string $modelName = 'activity';

    /**
     * Manuel form yok
     */
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    /**
     * TABLO
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Zaman')
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->placeholder('Sistem')
                    ->searchable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('İşlem')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        'view'   => 'info',
                        default  => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('resource')
                    ->label('Resource')
                    ->badge()
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('model_id')
                    ->label('Kayıt ID')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'create' => 'Create',
                        'update' => 'Update',
                        'delete' => 'Delete',
                        'view'   => 'View',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    /**
     * GÖRÜNTÜLE → INFOLIST
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('created_at')
                ->label('Zaman')
                ->since(),

            TextEntry::make('user.name')
                ->label('Kullanıcı')
                ->placeholder('Sistem'),

            TextEntry::make('action')
                ->label('İşlem')
                ->badge(),

            TextEntry::make('resource')
                ->label('Resource'),

            TextEntry::make('model')
                ->label('Model'),

            TextEntry::make('model_id')
                ->label('Kayıt ID'),

            TextEntry::make('ip')
                ->label('IP Adresi'),

            TextEntry::make('user_agent')
                ->label('Cihaz / Tarayıcı')
                ->columnSpanFull(),

            KeyValueEntry::make('old_data')
                ->label('Önceki Değerler')
                ->columnSpanFull()
                ->keyLabel('Alan')
                ->valueLabel('Değer')
                ->placeholder('Önceki veri yok')
                ->getStateUsing(fn ($record) =>
                    is_array($record->old_data) ? $record->old_data : []
                ),

            KeyValueEntry::make('new_data')
                ->label('Yeni Değerler')
                ->columnSpanFull()
                ->keyLabel('Alan')
                ->valueLabel('Değer')
                ->placeholder('Yeni veri yok')
                ->getStateUsing(fn ($record) =>
                    is_array($record->new_data) ? $record->new_data : []
                ),
        ]);
    }

    /**
     * SAYFALAR
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    /**
     * YETKİLER (AUDIT MANTIĞI)
     */
    // public static function canCreate(): bool
    // {
    //     return false; // Log manuel oluşturulmaz
    // }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user()?->can('view_' . static::$modelName);
    // }

    // public static function canEdit(Model $record): bool
    // {
    //     return false; // Log düzenlenmez
    // }

    // public static function canDelete(Model $record): bool
    // {
    //     return auth()->user()?->can('delete_' . static::$modelName);
    // }
}
