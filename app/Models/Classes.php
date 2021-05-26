<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table = "classes";

    public function sport_name(){
        return $this->hasOne('App\Models\Sports','id','sport_id');
    }
    
 	public function coach_name(){
        return $this->hasOne('App\Models\Employee','id','coach_id');
    }
}
