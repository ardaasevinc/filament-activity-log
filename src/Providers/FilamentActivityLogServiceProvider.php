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
        if ($this->app->runningInConsole()) {
        $this->publishes([
                dirname(__DIR__, 1)
                . '/database/migrations/create_activity_logs_table.php.stub'
                => database_path(
                        'migrations/' . date('Y_m_d_His') . '_create_activity_logs_table.php'
                    ),
        ], 'filament-activity-log-migrations');
    }
}

}
