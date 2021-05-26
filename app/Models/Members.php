<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    use HasFactory;
    protected $table = "members";


    public function sports_list(){
        return $this->hasMany('App\Models\MembersSports','member_id','id');
    }
}
