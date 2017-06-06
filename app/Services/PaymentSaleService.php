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
    private $detail;

    public function __construct(Payment $model, CashFlow $detail) {
        $this->model = $model;
        $this->detail = $detail;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('sale_no',function($model){
                return $model->sale->no;
            })
            ->addColumn('total',function($model){
                return number_format($model->sale->total);
            })
            ->addColumn('payment',function($model){
                return number_format($model->detail->sum('value'));
            })
            ->addColumn('status',function($model){
                return $model->detail->sum('value') >= $model->sale->total ? '<label class="label label-success">paid</label>'
                    : '<label class="label label-warning">unpaid</label>';
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->where('type','sale')
            ->make(true);
    }

    public function datatablesDetail($id) {
        return Datatables::eloquent($this->detail->query())
            ->addColumn('account_name',function($model){
                return $model->account_code->name;
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->editColumn('value', function ($model){
                return number_format($model->value);
            })
            ->addColumn('action','actions.payment_detail_sale')
            ->where('payment_id',$id)
            ->make(true);
    }

    public function detail() {

    }
}