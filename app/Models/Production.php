<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Production extends Model {
    protected $fillable = [
        'no','sale_order_id','cashier_id'
    ];

    public function sale_order() {
        return $this->belongsTo(SaleOrder::class);
    }

    public function cashier() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}