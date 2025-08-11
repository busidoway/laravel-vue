<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LongRunningQueueWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:long-running-worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the queue worker for a long period';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        while (true) {
            try {
                $this->call('queue:work', [
                    '--sleep' => 3,
                    '--tries' => 3,
                ]);
            } catch (\Exception $e) {
                Log::error('Queue worker failed: ' . $e->getMessage());
                // Возможно, стоит добавить паузу на случай, если ошибка повторяется часто
                // sleep(60);
            }
            // Пауза для снижения нагрузки
            sleep(3);
        }
    }
}
