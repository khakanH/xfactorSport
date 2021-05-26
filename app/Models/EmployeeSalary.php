<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;
    protected $table = "employee_salary";

    public function employee_data(){
        return $this->hasOne('App\Models\Employee','id','employee_id');
    }
}
