<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Order extends Model {
    use SoftDeletes;
    use RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 1000;

    protected $dates = ['paid_until_at','arrive_at'];
    protected $appends = ['total'];

    protected $fillable = [
        'supplier_id','payment_method_id','cashier_id','cash','paid_until_at',
        'arrive_at','invoice_no','delivery_order_no'
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

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function getTotalAttribute() {
        $orders = Order::find($this->attributes['id']);
        return $orders->transactions->sum(function ($detail){
            return ($detail['purchase_price'] - $detail['disc']) * $detail['qty'] * $detail['attribute'];
        });
    }

    public function payment() {
        return $this->hasOne(Payment::class,'ref_id')
            ->where('type','order');
    }

}