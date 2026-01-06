<?php

namespace Ardaasevinc\FilamentActivityLog\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-activity-log:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Filament Activity Log package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing Filament Activity Log...');

        // Config veya Migration yayınlama işlemleri burada tetiklenebilir
        // Şimdilik ServiceProvider'daki autoInstallOnce hallediyor, 
        // burayı manuel tetikleyici olarak bırakabilirsin.
        
        $this->call('vendor:publish', [
            '--tag' => 'filament-activity-log-migrations',
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'filament-activity-log-files',
        ]);

        $this->info('Filament Activity Log installed successfully.');

        return self::SUCCESS;
    }
}