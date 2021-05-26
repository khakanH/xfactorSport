<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;

use App\Traits\CommonTrait;
class EmployeeExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:employee_expiry';

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
       \Log::info("Employee Expiry Notify is working fine!");


        $emp = Employee::where('is_id_expire_notified',0)->whereDate('id_expiry_date','<=',date('Y-m-d'))->orderBy('created_at','asc')->get()->toArray();

        $names =array();
        foreach ($emp as $key) 
        {
            $names[] = $key['name']." - <small>ID Expired Date: ".date("M d, Y",strtotime($key['id_expiry_date']))."</small>";

            Employee::where('id',$key['id'])->update(array('is_id_expire_notified'=>1));
        }

        if (count($emp) != 0) 
        {
            $this->SaveNotification("Employees ID Expiry Alert",count($emp)." Employee(s) ID has been Expired.<br><b>Names:</b><br>".implode("<br>",$names),5,"fas fa-exclamation-triangle text-danger","employee.view-employee");
        }


        $this->info('Employee Expiry:Notify Command Run successfully!');
    }
}
