<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CronTest extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cron:test';

    /**
     * The console command description.
     */
    protected $description = 'Test if cron is running every minute';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Cron test ran at: ' . now()->toDateTimeString());
        $this->info('Cron test ran at: ' . now()->toDateTimeString());
    }
}