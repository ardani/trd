<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model {
    protected $fillable = [
        'account_code_id','value','note','giro','is_direct','payment_id'
    ];

    public function account_code() {
        return $this->belongsTo(AccountCode::class);
    }
}