<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersSports extends Model
{
    use HasFactory;
    protected $table = "members_sports";

    public function sport_name(){
        return $this->hasOne('App\Models\Sports','id','sport_id');
    }

    public function coach_name(){
        return $this->hasOne('App\Models\Employee','id','coach_id');
    }

    public function classes_list(){
        return $this->hasMany('App\Models\MembersSportsClasses','member_sports_id','id')->orderBy('days_sort')->orderBy('class_from_time');
    }


    public function member_name(){
        return $this->hasOne('App\Models\Members','id','member_id');
    }

}
