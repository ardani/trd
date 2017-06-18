<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model {
    protected $fillable = [
        'account_code_id','account_code_ref_id','credit',
        'debit','note','giro','is_direct','payment_id',
        'cash_id', 'from_to_id'
    ];

    public function account_code() {
        return $this->belongsTo(AccountCode::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}