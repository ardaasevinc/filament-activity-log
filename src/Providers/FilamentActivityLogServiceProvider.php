<?php

namespace Ardaasevinc\FilamentActivityLog\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Ardaasevinc\FilamentActivityLog\Commands\InstallCommand;

class FilamentActivityLogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /**
         * 🔑 1. Migration Yolu
         */
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        /**
         * 🔑 2. Publish Tanımları
         */
        $this->registerPublishes();

        if (!$this->app->runningInConsole()) {
            return;
        }

        /**
         * 🔑 3. Komutlar
         */
        $this->commands([
            InstallCommand::class,
        ]);

        /**
         * 🔑 4. Otomatik Kurulum (Auto Install)
         */
        $this->autoInstallOnce();
    }

    protected function registerPublishes(): void
    {
        /**
         * A. Migration Dosyası
         */
        $this->publishes([
            __DIR__ . '/../database/migrations/create_activity_logs_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His') . '_create_activity_logs_table.php'),
        ], 'filament-activity-log-migrations');

        /**
         * B. Uygulama Dosyaları (Resource, Model, Service, Trait)
         */
        $this->publishes([
            // 1. Model
            __DIR__ . '/../Models/ActivityLog.php'
            => app_path('Models/ActivityLog.php'),

            // 2. Service
            __DIR__ . '/../Services/FilamentActivityLogger.php'
            => app_path('Services/FilamentActivityLogger.php'),

            // 3. TRAIT (BURAYA EKLENDİ) ✅
            // Kullanıcının projesine: app/Filament/Concerns/HasActivityLogger.php olarak gider.
            __DIR__ . '/../Concerns/HasActivityLogger.php'
            => app_path('Filament/Concerns/HasActivityLogger.php'),

            // 4. Resource Ana Dosyası
            __DIR__ . '/../Filament/Resources/ActivityLogResource.php'
            => app_path('Filament/Resources/ActivityLogResource.php'),

            // 5. Resource Sayfaları
            __DIR__ . '/../Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php'
            => app_path('Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php'),

            // (DÜZELTME: Dosya adı tekil yapıldı 'ViewActivityLog.php')
            __DIR__ . '/../Filament/Resources/ActivityLogResource/Pages/ViewActivityLog.php'
            => app_path('Filament/Resources/ActivityLogResource/Pages/ViewActivityLog.php'),

        ], 'filament-activity-log-files');
    }

    protected function autoInstallOnce(): void
    {
        $marker = base_path('.filament-activity-log.installed');

        if (File::exists($marker)) {
            return;
        }

        // Migration yoksa yayınla
        if (!$this->migrationExists()) {
            Artisan::call('vendor:publish', [
                '--tag' => 'filament-activity-log-migrations',
                '--force' => false,
            ]);
        }

        // Dosyaları eksikse yayınla
        $this->publishIfMissing(app_path('Models/ActivityLog.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Filament/Resources/ActivityLogResource.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Services/FilamentActivityLogger.php'), 'filament-activity-log-files');

        // TRAIT KONTROLÜ (BURAYA EKLENDİ) ✅
        $this->publishIfMissing(app_path('Filament/Concerns/HasActivityLogger.php'), 'filament-activity-log-files');

        File::put($marker, now()->toDateTimeString());
    }

    protected function publishIfMissing(string $targetPath, string $tag): void
    {
        if (File::exists($targetPath)) {
            return;
        }

        // Sadece ilgili dosyayı çekmek yerine tüm tag grubunu yayınlar,
        // ancak Laravel'in publish mekanizması var olan dosyaları ezmez.
        Artisan::call('vendor:publish', [
            '--tag' => $tag,
            '--force' => false,
        ]);
    }

    protected function migrationExists(): bool
    {
        return !empty(glob(database_path('migrations/*_create_activity_logs_table.php')));
    }
}