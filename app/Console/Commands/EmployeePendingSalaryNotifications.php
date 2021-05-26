<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\EmployeeSalary;



use App\Traits\CommonTrait;
class EmployeePendingSalaryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:employee_pending_salary';

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
        \Log::info("Employee Pending Salary Notify is working fine!");

        $get_emp_ids = EmployeeSalary::whereMonth('salary_date',date('m'))->whereYear('salary_date',date('Y'))->pluck('employee_id');

        $emp = Employee::where('is_salary_notified',0)->whereNotIn('id',$get_emp_ids)->orderBy('created_at','asc')->get()->toArray();

        $names =array();
        foreach ($emp as $key) 
        {
            $names[] = $key['name'];

            Employee::where('id',$key['id'])->update(array('is_salary_notified'=>1));
        }

        if (count($emp) != 0) 
        {
            $this->SaveNotification("Employees Pending Salary Alert",count($emp)." Employee(s) doesnot receive salary for current month (".date('F').").<br><b>Names:</b><br>".implode(" ,&nbsp;&nbsp;",$names),4,"fas fa-exclamation-triangle text-warning","employee.view-salary");
        }


        $this->info('Employee Pending Salary:Notify Command Run successfully!');
    }
}
