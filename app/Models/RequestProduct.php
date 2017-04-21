<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RequestProduct extends Model {
    protected $fillable = [
         'note','no','cashier_id'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function state() {
        return $this->belongsTo(State::class);
    }
}