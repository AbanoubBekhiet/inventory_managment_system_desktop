<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function relation(){
        return $this->belongsTo(User::class);
    }
    public function relation2(){
        return $this->hasMany(Bill::class);
    }
}
