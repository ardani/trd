<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductDiscount extends Model {
    protected $dates = ['expired_at'];
    protected $fillable = [
        'product_id','amount','expiret_at'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}