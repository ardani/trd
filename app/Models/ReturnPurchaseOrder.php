<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReturnPurchaseOrder extends Model {
    protected $dates = ['arrive_at'];
    protected $fillable = [
        'no','purchase_order_id','cashier_id','note','arrive_at'
    ];

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function cashier() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }
}