<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassesDays extends Model
{
    use HasFactory;
    protected $table = "classes_days";


     public function class_data(){
        return $this->hasOne('App\Models\Classes','id','class_id');
    }
}
