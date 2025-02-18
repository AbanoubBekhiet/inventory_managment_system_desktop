<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    public function relation(){
        return $this->belongsTo(Product::class);
    }
    public function relation2(){
        return $this->belongsTo(User::class);
    }
}
