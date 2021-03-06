<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductUnit extends Model {
    protected $fillable = [
        'product_id','component_unit_code','value', 'unit_id'
    ];

    public function component_unit() {
        return $this->belongsTo(ComponentUnit::class);
    }
}