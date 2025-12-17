<?php

namespace App\Filament\Concerns;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\FilamentActivityLogger;

trait HasActivityLogger
{
    /**
     * GÖRÜNTÜLEME
     * (Sadece Edit sayfası açılınca çalışır)
     */
    protected function afterFill(): void
    {
        if (! $this->record) {
            return;
        }

        FilamentActivityLogger::log(
            'view',
            static::getResource(),
            $this->record
        );
    }

    /**
     * CREATE
     */
    protected function afterCreate(): void
    {
        if (! $this->record) {
            return;
        }

        FilamentActivityLogger::log(
            'create',
            static::getResource(),
            $this->record,
            null,
            $this->record->toArray()
        );
    }

    /**
     * UPDATE
     */
    protected function afterSave(): void
    {
        if (! $this->record) {
            return;
        }

        FilamentActivityLogger::log(
            'update',
            static::getResource(),
            $this->record,
            $this->record->getOriginal(),
            $this->record->getChanges()
        );
    }

    /**
     * DELETE
     */
    protected function beforeDelete(): void
    {
        if (! $this->record) {
            return;
        }

        FilamentActivityLogger::log(
            'delete',
            static::getResource(),
            $this->record,
            $this->record->toArray(),
            null
        );
    }

    /**
     * DELETE BUTONU
     * (SADECE EditRecord sayfasında gösterilir)
     */
    protected function getHeaderActions(): array
    {
        if (! $this instanceof EditRecord) {
            return [];
        }

        return [
            Actions\DeleteAction::make(),
        ];
    }
}
