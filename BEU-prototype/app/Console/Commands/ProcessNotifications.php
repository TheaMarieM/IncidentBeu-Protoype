<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class ProcessNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending parent notifications (SMS/Email)';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Processing pending notifications...');
        
        $notificationService->processPendingNotifications();
        
        $this->info('Notifications processed successfully.');
        
        return Command::SUCCESS;
    }
}
