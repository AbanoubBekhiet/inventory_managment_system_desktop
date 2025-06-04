<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
        use HasFactory;

    public function relation(){
        return $this->belongsTo(User::class);
    }
   
    public function relation2(){
        return $this->belongsToMany(Bill::class, 'product_bill_relation')
        ->withPivot('number_of_pieces', 'number_of_packets', 'total_product_price');    }
    public function relation3(){
        return $this->belongsTo(Category::class);
    }
    public function relation4(){
        return $this->hasMany(Price::class);
    }
}
