<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = "employee";


    public function job_type_name(){
        return $this->hasOne('App\Models\JobType','id','job_type_id');
    }

    public function sport_name(){
        return $this->hasOne('App\Models\Sports','id','sport_id');
    }
}
