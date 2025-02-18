<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBillRelation extends Model
{
    public $incrementing = false; 
    protected $primaryKey = ['product_id', 'bill_id'];

    protected $fillable = [
        'product_id', 'bill_id', 'number_of_packets', 'number_of_pieces', 'total_product_price','packet_price','piece_price'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
