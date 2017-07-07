<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model {
    protected $fillable = [
        'no','type','cashier_id','note'
    ];

    protected $appends = ['total'];

    public function details() {
        return $this->hasMany(CashFlow::class)
            ->where('account_code_id', '!=', $this->attributes['account_cash_id']);
    }

    public function account_cash() {
        return $this->belongsTo(AccountCode::class, 'account_cash_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class,'cashier_id');
    }

    public function getTotalAttribute() {
        $cash = Cash::find($this->attributes['id']);
        $account_cash_id = $this->attributes['account_cash_id'];
        $type = $this->attributes['type'];
        return $cash->details
            ->reject(function ($value) use ($account_cash_id) {
                return $value['account_code_id'] == $account_cash_id;
            })
            ->sum(function ($detail) use ($type) {
                return $type ? $detail['debit'] : $detail['credit'];
            });
    }
}