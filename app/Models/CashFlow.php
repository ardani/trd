<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model {
    protected $fillable = [
        'account_code_id','value','note','referenceable_id','referenceable_type'
    ];

    public function referenceable() {
        return $this->morphTo();
    }

    public function account_code() {
        return $this->belongsTo(AccountCode::class);
    }
}