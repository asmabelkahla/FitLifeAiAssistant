<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class UpdateSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-status';

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
        $now = Carbon::now();

            // Met à jour les abonnements expirés
            Subscription::where('end_date', '<', $now)
            ->where('status', 'active')
            ->update(['status' => 'expired']);
            // Met à jour les abonnements annulés (si applicable)
        Subscription::where('status', 'canceled')
        ->update(['status' => 'canceled']); 

    $this->info('Subscription statuses updated successfully.');



    }
}
