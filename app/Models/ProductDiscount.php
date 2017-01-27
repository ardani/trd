<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductDiscount extends Model {
    protected $fillable = [
        'product_id','amount','type'
    ];
}