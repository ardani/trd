<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrectionStock extends Model {
    protected $fillable = [
        'product_id','correction','purchase_price'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}