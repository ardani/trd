<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model {
    protected $dates = ['paid_until_at'];
    protected $fillable = [
        'customer_id','cashier_id','payment_method_id','cash','disc','paid_until_at'
    ];

    public function payment_method() {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}