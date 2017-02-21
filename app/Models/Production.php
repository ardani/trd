<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Production extends Model {
    protected $fillable = [
        'no','purchase_order_id','cashier_id','note'
    ];

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function cashier() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}