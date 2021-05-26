<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MembersSports;


use App\Traits\CommonTrait;
class MembersSportsExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:members_sports_expiry';

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
        \Log::info("Members Sports Expiry Notify is working fine!");

        $get_mem_sports = MembersSports::where('is_notified',0)->get();

        foreach ($get_mem_sports as $key) 
        {
            $plus_one_month = strtotime("+1 month",strtotime($key['expiry_date']));
            
            if (time() >= $plus_one_month) 
            {
                $this->SaveNotification("Members Sport Expiry Alert",$key->member_name->name."'s Membership has been expired for more than 1 month.<br>Expired Date: ".date("M d, Y",strtotime($key['expiry_date']))."<br>Sport: ".$key->sport_name->name."<br>Coach: ".$key->coach_name->name,2,"fas fa-exclamation-triangle text-warning","register.view-register");

                    MembersSports::where('id',$key['id'])->update(array('is_notified'=>1));
            }            


        }


        $this->info('Members Sports Expiry:Notify Command Run successfully!');
        
    }
}
