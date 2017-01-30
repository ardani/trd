<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductPrice extends Model {
    protected $fillable = [
        'product_id','customer_type_id','selling_price','purchase_price'
    ];

    public function customer_type() {
        return $this->belongsTo(CustomerType::class);
    }
}