<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CashFlow extends Model {
    protected $fillable = [
        'account_code_id','value','note','reference_key'
    ];

    public function account_code() {
        return $this->belongsTo(AccountCode::class);
    }
}