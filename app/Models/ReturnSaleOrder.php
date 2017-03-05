<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReturnSaleOrder extends Model {
    protected $dates = ['arrive_at'];
    protected $fillable = [
        'no','sale_order_id','cashier_id','note','arrive_at','is_complete'
    ];

    public function sale_order() {
        return $this->belongsTo(SaleOrder::class);
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function cashier() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }
}