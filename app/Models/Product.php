<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use SoftDeletes;
    protected $fillable = [
        'code','name', 'start_stock','min_stock','description','selling_price_default','supplier_id',
        'purchase_price_default','category_id','unit_id','can_sale'
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
        $trans = Transaction::where('product_id',$this->attributes['id'])
            ->select(\DB::raw('sum(qty*attribute) as stock'))
            ->where('return_complete',0)
            ->first();
        $units = ProductUnit::where('product_id',$this->attributes['id'])->get(['value']);
        $stock_unit = 1;
        if ($units) {
            $stock_unit = $units->reduce(function ($carry, $item) {
                return $carry * $item->value;
            },1);
        }
        return $this->attributes['stock'] = ($this->attributes['start_stock'] * $stock_unit) + $trans->stock;
    }
}