<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReturnSale extends Model {
    protected $dates = ['arrive_at'];
    protected $fillable = [
        'sale_id','cashier_id','note','arrive_at'
    ];

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function cashier() {
        return $this->belongsTo(Employee::class);
    }
}