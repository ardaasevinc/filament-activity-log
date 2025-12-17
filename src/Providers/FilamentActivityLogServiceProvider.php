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
        // Publish tanımları (migrations + dosyalar)
        $this->registerPublishes();

        if (!$this->app->runningInConsole()) {
            return;
        }

        // Artisan command'leri register et
        $this->commands([
            InstallCommand::class,
        ]);

        // composer require sonrası 1 kere otomatik kurulum
        $this->autoInstallOnce();
    }

    protected function registerPublishes(): void
    {
        // ✅ Stub path: src/database/migrations/...
        $this->publishes([
            __DIR__ . '/../database/migrations/create_activity_logs_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His') . '_create_activity_logs_table.php'),
        ], 'filament-activity-log-migrations');

        // ✅ Model / Filament Resource / Pages / Services / Trait (istersen publish tag ile de alınabilir)
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
        // Sen storage kullanmıyorum demiştin; o yüzden proje kökünde marker file:
        $marker = base_path('.filament-activity-log.installed');

        if (File::exists($marker)) {
            return; // zaten kuruldu
        }

        // 1) Migration publish (zaten varsa tekrar üretmeyelim)
        if (!$this->migrationExists()) {
            Artisan::call('vendor:publish', [
                '--tag' => 'filament-activity-log-migrations',
                '--force' => false,
            ]);
        }

        // 2) Dosyaları publish et (varsa ezme)
        $this->publishIfMissing(app_path('Models/ActivityLog.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Filament/Resources/ActivityLogResource.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Services/FilamentActivityLogger.php'), 'filament-activity-log-files');
        $this->publishIfMissing(app_path('Filament/Concerns/HasActivityLogger.php'), 'filament-activity-log-files');

        // 3) Marker oluştur
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
