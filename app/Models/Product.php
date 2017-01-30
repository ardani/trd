<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use SoftDeletes;
    protected $fillable = [
        'code','name', 'start_stock','min_stock','description','selling_price_default','supplier_id',
        'purchase_price_default','category_id','unit_id'
    ];

    protected $appends = ['stock'];

    public function product_unit() {
        return $this->hasMany(ProductUnit::class);
    }

    public function product_price() {
        return $this->hasMany(ProductPrice::class);
    }

    public function product_stock() {
        return $this->hasMany(ProductStock::class);
    }

    public function product_discount() {
        return $this->hasOne(ProductDiscount::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function getStockAttribute() {
        $stock = ProductStock::where('product_id',$this->attributes['id'])->sum('value');
        return $this->attributes['stock'] = $this->attributes['start_stock'] + $stock;
    }
}