<?php

namespace App\Filament\Concerns;

use App\Services\FilamentActivityLogger;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

trait HasActivityLogger
{
    /**
     * Güncelleme öncesi eski veriyi tutmak için geçici değişken
     */
    protected array $oldData = [];

    /**
     * GÖRÜNTÜLEME (VIEW)
     * Edit veya View sayfası açıldığında çalışır.
     */
    protected function afterFill(): void
    {
        // Sadece kayıt varsa ve Edit/View sayfalarındaysak logla
        if (
            $this->record &&
            ($this instanceof EditRecord || $this instanceof ViewRecord)
        ) {
            FilamentActivityLogger::log(
                event: 'view',
                resource: static::getResource(),
                model: $this->record
            );
        }
    }

    /**
     * OLUŞTURMA (CREATE)
     * Kayıt oluşturulduktan hemen sonra çalışır.
     */
    protected function afterCreate(): void
    {
        if (! $this->record) {
            return;
        }

        FilamentActivityLogger::log(
            event: 'create',
            resource: static::getResource(),
            model: $this->record,
            oldData: null,
            newData: $this->record->toArray()
        );
    }

    /**
     * GÜNCELLEME ÖNCESİ (BEFORE UPDATE)
     * Kayıt güncellenmeden önceki halini hafızaya alıyoruz.
     */
    protected function beforeSave(): void
    {
        if ($this->record && $this instanceof EditRecord) {
            // Değişiklik yapılmamış ham veriyi (original) sakla
            $this->oldData = $this->record->toArray();
        }
    }

    /**
     * GÜNCELLEME SONRASI (AFTER UPDATE)
     * Kayıt güncellendikten sonra çalışır.
     */
    protected function afterSave(): void
    {
        if (! $this->record) {
            return;
        }

        // Sadece değişen alanları (dirty) almak istersen: $this->record->getChanges()
        // Tüm son hali almak istersen: $this->record->toArray()

        FilamentActivityLogger::log(
            event: 'update',
            resource: static::getResource(),
            model: $this->record,
            oldData: $this->oldData, // beforeSave'den gelen eski veri
            newData: $this->record->toArray() // Yeni güncel veri
        );
    }

    /**
     * SİLME (DELETE)
     * Kayıt silinmeden hemen önce çalışır.
     */
    protected function beforeDelete(): void
    {
        if (! $this->record) {
            return;
        }

        FilamentActivityLogger::log(
            event: 'delete',
            resource: static::getResource(),
            model: $this->record,
            oldData: $this->record->toArray(),
            newData: null
        );
    }

    /*
     * DİKKAT: getHeaderActions metodu buradan kaldırılmıştır.
     * Bu metodu Trait içinde tanımlamak, sayfadaki diğer butonları (Save, Cancel) 
     * devre dışı bırakabilir veya çakışma yaratabilir.
     * Delete butonu zaten Filament EditRecord sayfasında varsayılan olarak gelir.
     */
}