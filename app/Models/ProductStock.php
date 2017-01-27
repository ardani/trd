<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductStock extends Model {
    protected $fillable = [
        'product_id','stockable_id','stockable_type','value'
    ];
}