<?php

namespace ArdaSevinc\FilamentActivityLog\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Şimdilik boş.
        // İleride config, binding, singleton eklenebilir.
    }

    public function boot(): void
    {
        // Paket migration'larını otomatik yükle
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // (İleride) config publish etmek istersek hazır olsun
        // $this->publishes([
        //     __DIR__ . '/../config/filament-activity-log.php' => config_path('filament-activity-log.php'),
        // ], 'filament-activity-log-config');
    }
}
