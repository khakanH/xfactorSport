<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltExam extends Model
{
    use HasFactory;
    protected $table = "belt_exam";

    public function member_info(){
        return $this->hasOne('App\Models\Members','id','member_id');
    }

    public function sport_name(){
        return $this->hasOne('App\Models\Sports','id','sport_id');
    }

    public function coach_name(){
        return $this->hasOne('App\Models\Employee','id','coach_id');
    }

    public function current_belt_name(){
        return $this->hasOne('App\Models\Belt','id','current_belt_id');
    }

    public function next_belt_name(){
        return $this->hasOne('App\Models\Belt','id','next_belt_id');
    }

}
