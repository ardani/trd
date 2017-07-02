<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TakeProduct extends Model {
    protected $fillable = [
        'product_id','qty','cashier_id','units','attribute'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}