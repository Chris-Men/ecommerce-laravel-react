<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $logFiles = glob(storage_path('logs/*.log'));

    foreach ($logFiles as $logFile) {
        unlink($logFile);
    }

    $this->info('Archivos de log eliminados correctamente.');
}

}
