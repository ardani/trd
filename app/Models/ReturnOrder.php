<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReturnOrder extends Model {
    protected $dates = ['arrive_at'];
    protected $fillable = [
        'no','order_id','cashier_id','note','arrive_at','is_complete'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function cashier() {
        return $this->belongsTo(Employee::class);
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}