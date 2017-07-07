<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model {
    protected $fillable = [
        'account_code_id','account_code_ref_id','credit',
        'debit','note','giro','is_direct','payment_id',
        'cash_id', 'from_to_id', 'created_at'
    ];

    public function account_code() {
        return $this->belongsTo(AccountCode::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

    public function from_to_account_code() {
        return $this->belongsTo(AccountCode::class, 'from_to_id');
    }

    public function cash() {
        return $this->belongsTo(Cash::class, 'cash_id', 'id');
    }

    protected static function boot() {
        parent::boot();
        static::saved(function($model) {
            if ($model->payment_id) {
                switch ($model->payment->type) {
                    case 'sale':
                        $sale = SaleOrder::find($model->payment->ref_id);
                        $sale->paid_status = ($model->payment->total < $model->payment->sale->total) ? 0 : 1;
                        $sale->save();
                        break;
                    case 'order':
                        $order = Order::find($model->payment->ref_id);
                        $order->paid_status = ($model->payment->total < $model->payment->order->total) ? 0 : 1;
                        $order->save();
                        break;
                }
            }
        });
    }
}