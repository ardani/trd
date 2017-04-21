<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $dates = ['stock_at'];
    protected $fillable = [
        'code','name', 'start_stock','min_stock','description','selling_price_default','supplier_id',
        'purchase_price_default','category_id','unit_id','can_sale','stock_at'
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
        if (in_array($this->attributes['category_id'], [2,4])) {
            return 0;
        }

        $trans = Transaction::where('product_id', $this->attributes['id'])
            ->whereNotIn('transactionable_type',['App\Models\SaleOrder','App\Models\RequestProduct'])
            ->select(\DB::raw('sum(qty*attribute) as stock'))
            ->where('return_complete',0)
            ->first();
        $correction = CorrectionStock::where('product_id', $this->attributes['id'])
            ->select(\DB::raw('sum(qty*attribute) as stock'))
            ->first();
        $takeProduct = TakeProduct::where('product_id', $this->attributes['id'])
            ->select(\DB::raw('sum(qty) as stock'))
            ->first();
        return $this->attributes['stock'] = $this->attributes['start_stock'] +
            $trans->stock - $correction->stock - $takeProduct->stock;
    }
}