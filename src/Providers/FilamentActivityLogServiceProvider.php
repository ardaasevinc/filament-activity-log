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
         * 🔑 1. Laravel’e paketin migration path’ini tanıt
         * (publish edilmeden bile migrationExists gibi kontroller sağlıklı çalışır)
         */
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        /**
         * 🔑 2. Publish tanımları
         */
        $this->registerPublishes();

        if (!$this->app->runningInConsole()) {
            return;
        }

        /**
         * 🔑 3. Artisan command
         */
        $this->commands([
            InstallCommand::class,
        ]);

        /**
         * 🔑 4. composer require sonrası tek seferlik auto install
         */
        $this->autoInstallOnce();
    }

    protected function registerPublishes(): void
    {
        /**
         * Migration stub → gerçek migration
         */
        $this->publishes([
            __DIR__ . '/../database/migrations/create_activity_logs_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His') . '_create_activity_logs_table.php'),
        ], 'filament-activity-log-migrations');

        /**
         * App içine kopyalanacak dosyalar
         */
        $this->publishes([
            __DIR__ . '/../Models/ActivityLog.php'
            => app_path('Models/ActivityLog.php'),

            __DIR__ . '/../Services/FilamentActivityLogger.php'
            => app_path('Services/FilamentActivityLogger.php'),

            __DIR__ . '/../Concerns/HasActivityLogger.php'
            => app_path('Filament/Concerns/HasActivityLogger.php'),

            __DIR__ . '/../Filament/Resources/ActivityLogResource.php'
            => app_path('Filament/Resources/ActivityLogResource.php'),

            __DIR__ . '/../Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php'
            => app_path('Filament/Resources/ActivityLogResource/Pages/ListActivityLogs.php'),

            __DIR__ . '/../Filament/Resources/ActivityLogResource/Pages/ViewActivityLogs.php'
            => app_path('Filament/Resources/ActivityLogResource/Pages/ViewActivityLogs.php'),
        ], 'filament-activity-log-files');
    }

    protected function autoInstallOnce(): void
    {
        $marker = base_path('.filament-activity-log.installed');

        if (File::exists($marker)) {
            return;
        }

        // Migration yoksa publish et
        if (!$this->migrationExists()) {
            Artisan::call('vendor:publish', [
                '--tag' => 'filament-activity-log-migrations',
                '--force' => false,
            ]);
        }

        // Dosyalar
        $this->publishIfMissing(app_path('Models/ActivityLog.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Filament/Resources/ActivityLogResource.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Services/FilamentActivityLogger.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Filament/Concerns/HasActivityLogger.php'), 'filament-activity-log-files');

        File::put($marker, now()->toDateTimeString());
    }

    protected function publishIfMissing(string $targetPath, string $tag): void
    {
        if (File::exists($targetPath)) {
            return;
        }

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
