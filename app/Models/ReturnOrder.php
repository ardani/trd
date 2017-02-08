<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReturnOrder extends Model {
    protected $dates = ['arrive_at'];
    protected $fillable = [
        'no','order_id','cashier_id','note','arrive_at'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function cashier() {
        return $this->belongsTo(Employee::class);
    }
}