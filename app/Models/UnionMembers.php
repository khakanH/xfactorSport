<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionMembers extends Model
{
    use HasFactory;
    protected $table = "union_members";

    public function member_name(){
        return $this->hasOne('App\Models\Members','id','member_id');
    }

    public function union_name(){
        return $this->hasOne('App\Models\Unions','union_id','union_id');
    }
}
