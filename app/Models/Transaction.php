<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model {
    protected $fillable = [
        'transactionable_type','transactionable_id',
        'purchase_price','selling_price','qty',
        'product_id','disc','return_qty',
        'attribute',
        'units',
        'desc',
        'return_complete',
        'production_product_id',
        'status',
        'created_at'
    ];

    public function transactionable() {
        return $this->morphTo();
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}