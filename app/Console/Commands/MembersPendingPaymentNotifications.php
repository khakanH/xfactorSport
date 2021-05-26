<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Members;


use App\Traits\CommonTrait;
class MembersPendingPaymentNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:members_pending_payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    
    use CommonTrait;
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
       \Log::info("Members Pending Payment Notify is working fine!");

        $get_mem = Members::where('is_notified',0)->where('remaining_amount','>',0)->get();

        foreach ($get_mem as $key) 
        {
                $this->SaveNotification("Members Pending Payment Alert",$key['name']."'s has ramaining amount of ".$key['remaining_amount']." to be paid for membership fees.<br>Registered Date: ".date("M d, Y",strtotime($key['created_at']))."<br>Email: ".$key['email']."<br>Phone: ".$key['phone'],3,"fas fa-exclamation-triangle text-danger","register.view-register");

                    Members::where('id',$key['id'])->update(array('is_notified'=>1));

        }


        $this->info('Members Pending Payment:Notify Command Run successfully!');
    }
}
