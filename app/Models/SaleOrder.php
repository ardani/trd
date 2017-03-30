<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SaleOrder extends Model {
    protected $dates = ['paid_until_at'];
    protected $appends = ['total'];
    protected $fillable = [
        'customer_id','cashier_id','payment_method_id','cash','disc','paid_until_at', 'cash_flows_id', 'note'
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

    public function sale_order_state() {
        return $this->hasOne(SaleOrderState::class)->orderBy('created_at','desc');
    }

    public function getTotalAttribute() {
        $purchase = SaleOrder::find($this->attributes['id']);
        return $purchase->transactions->sum(function ($detail){
            return ($detail['selling_price']-$detail['disc']) * abs($detail['qty']);
        });
    }
}