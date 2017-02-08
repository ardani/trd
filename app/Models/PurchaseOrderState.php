<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PurchaseOrderState extends Model {
    protected $fillable = ['purchase_order_id','state_id'];

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class);
    }
}