<?php

namespace App\Models;
use App\Services\CashFlowService;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'cashier_id','type','ref_id'
    ];

    protected $appends = ['total'];

    public function order() {
        return $this->belongsTo(Order::class,'ref_id');
    }

    public function sale() {
        return $this->belongsTo(SaleOrder::class,'ref_id');
    }

    public function detail() {
        return $this->hasMany(CashFlow::class)->whereIn('account_code_id', $this->listAccount());
    }

    private function listAccount() {
        return AccountCode::where(['type' => 1,'parent' => 0])
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function getTotalAttribute() {
        $cash = CashFlow::where('payment_id',$this->attributes['id'])->get();
        if ($cash) {
            return $cash->sum(function ($value) {
                return in_array($value['account_code_id'], $this->listAccount()) ? abs($value['debit'] - $value['credit']) : 0;
            });
        }
        return 0;
    }
}