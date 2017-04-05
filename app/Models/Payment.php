<?php

namespace App\Models;
use App\Services\CashFlowService;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'cashier_id','type','ref_id'
    ];

    protected $appends = ['total'];

    public function order() {
        return $this->belongsTo(Order::class,'ref_id');
    }

    public function sale() {
        return $this->belongsTo(SaleOrder::class,'ref_id');
    }

    public function detail() {
        return $this->hasMany(CashFlow::class);
    }

    public function getTotalAttribute() {
        return CashFlow::where('payment_id',$this->attributes['id'])->sum('value');
    }
}