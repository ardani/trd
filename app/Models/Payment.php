<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'cashier_id','type','ref_id'
    ];

    public function order() {
        return $this->belongsTo(Order::class,'ref_id','id');
    }

    public function sale() {
        return $this->belongsTo(SaleOrder::class,'ref_id','id');
    }

    public function detail() {
        return $this->hasMany(CashFlow::class);
    }
}