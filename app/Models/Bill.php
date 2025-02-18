<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    public function relation(){
        return $this->belongsTo(Customer::class);
    }
    public function relation2(){
        return $this->belongsToMany(Product::class, 'product_bill_relation')
        ->withPivot('number_of_pieces', 'number_of_packets', 'total_product_price','piece_price','packet_price');    }
}
