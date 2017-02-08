<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PurchaseOrder extends Model {
    protected $dates = ['paid_until_at'];
    protected $appends = ['total'];
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

    public function purchase_order_state() {
        return $this->hasOne(PurchaseOrderState::class)->orderBy('created_at','desc');
    }

    public function getTotalAttribute() {
        $purchase = PurchaseOrder::find($this->attributes['id']);
        return $purchase->transactions->sum(function ($detail){
            return ($detail['selling_price']-$detail['disc']) * $detail['qty'];
        });
    }
}