<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
    protected $dates = [
        'paid_until_at','arrive_at'
    ];

    protected $fillable = [
        'supplier_id','payment_method_id','cashier_id','cash','paid_until_at','arrive_at'
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function payment_method() {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }

}