<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\CashFlow;
use App\Models\Payment;
use Entrust;
use Datatables;

class PaymentSaleService extends Service {

    protected $model;
    protected $name = 'payment_sales';

    public function __construct(Payment $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('sale_no',function($model){
                return $model->sale->no;
            })
            ->addColumn('total',function($model){
                return $model->sale->total;
            })
            ->addColumn('payment',function($model){
                return $model->detail->sum('value');
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->where('type','sale')
            ->make(true);
    }
}