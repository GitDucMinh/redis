<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Jobs\SendSubscriptionExpireMessageJob;
use Carbon\Carbon;

class SubscriptionExpiryNotifycation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:SubscriptionExpiryNotifycation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check which subscribed users have been expired';

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
        $expired_customers = Customer::where('subscribtion_end_date', '<', now())->get();
       
        foreach($expired_customers as $customer){
            info($customer->email);
            $expire_date = Carbon::createFromFormat('Y-m-d', $customer->subscribtion_end_date)->toDateString();
            dispatch(new SendSubscriptionExpireMessageJob($customer, $expire_date));
        }

    }
}
