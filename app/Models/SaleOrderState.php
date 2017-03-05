<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SaleOrderState extends Model {
    protected $fillable = ['sale_order_id','state_id'];

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function purchase_order() {
        return $this->belongsTo(SaleOrder::class);
    }
}