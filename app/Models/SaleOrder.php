<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class SaleOrder extends Model {
    use SoftDeletes;
    use RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 1000;

    protected $dates = ['paid_until_at'];
    protected $appends = ['total'];
    protected $fillable = [
        'customer_id','cashier_id','payment_method_id','cash',
        'disc','paid_until_at','cash_flows_id','note','state_id',
        'paid_status','shop_id'
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

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function getTotalAttribute() {
        $purchase = SaleOrder::find($this->attributes['id']);
        return $purchase->transactions->sum(function ($detail){
            return ($detail['selling_price'] - $detail['disc']) * abs($detail['qty']) * $detail['attribute'];
        });
    }

    public function payment() {
        return $this->hasOne(Payment::class,'ref_id')
            ->where('type','sale');
    }

    public function shop() {
        return $this->belongsTo(Shop::class);
    }
}