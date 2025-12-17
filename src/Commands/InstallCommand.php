<?php

namespace Ardaasevinc\FilamentActivityLog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'filament-activity-log:install {--force : Overwrite existing files}';

    protected $description = 'Install Filament Activity Log package (publish migrations and files)';

    public function handle(): int
    {
        $this->info('Installing Filament Activity Log...');

        // 1️⃣ Migration
        if (!$this->migrationExists() || $this->option('force')) {
            Artisan::call('vendor:publish', [
                '--tag'   => 'filament-activity-log-migrations',
                '--force' => $this->option('force'),
            ]);

            $this->info('✔ Migration published');
        } else {
            $this->line('• Migration already exists, skipped');
        }

        // 2️⃣ Files
        Artisan::call('vendor:publish', [
            '--tag'   => 'filament-activity-log-files',
            '--force' => $this->option('force'),
        ]);

        $this->info('✔ Files published');

        $this->info('🎉 Filament Activity Log installed successfully.');
        $this->line('→ Run: php artisan migrate');

        return self::SUCCESS;
    }

    protected function migrationExists(): bool
    {
        return !empty(glob(database_path('migrations/*_create_activity_logs_table.php')));
    }
}
