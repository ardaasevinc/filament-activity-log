<?php

namespace Ardaasevinc\FilamentActivityLog\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Panel;
use Ardaasevinc\FilamentActivityLog\Filament\Resources\ActivityLogResource;

class FilamentActivityLogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 🔹 Migration publish
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__) . '/database/migrations/create_activity_logs_table.php.stub'
                => database_path(
                        'migrations/' . date('Y_m_d_His') . '_create_activity_logs_table.php'
                    ),
            ], 'filament-activity-log-migrations');
        }

        // 🔥 Filament v3 Resource registration (DOĞRU YOL)
        Filament::registerPanels([
            function (Panel $panel): Panel {
                return $panel->resources([
                    ActivityLogResource::class,
                ]);
            },
        ]);
    }
}
