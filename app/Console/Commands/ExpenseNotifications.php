<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expenses;


use App\Traits\CommonTrait;

class ExpenseNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:expense';

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
        \Log::info("Expense Notify is working fine!");


            $get_expenses = Expenses::where('is_notified',0)->where('next_payment_date','!=','0000-00-00')->get(); 

            foreach ($get_expenses as $key) 
            {
                if (time() >= strtotime("-".$key->ExpenseTypeName->notification_period." day",strtotime($key['next_payment_date']))) 
                {
                    $this->SaveNotification("Expense Payment Alert","You have to pay your ".$key->ExpenseTypeName->name." payment on ".date("M d, Y",strtotime($key['next_payment_date'])),1,"fas fa-exclamation-triangle text-danger","expenses.view-expenses");

                    Expenses::where('id',$key['id'])->update(array('is_notified'=>1));


                }
            }



        $this->info('Expense:Notify Command Run successfully!');
        
    }
}
