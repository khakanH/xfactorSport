<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $table = "leaves";


    public function leave_type_name(){
        return $this->hasOne('App\Models\LeaveType','id','leave_type_id');
    }

    public function employee_data(){
        return $this->hasOne('App\Models\Employee','id','employee_id');
    }
}

