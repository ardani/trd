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

class PaymentOrderService extends Service {

    protected $model;
    protected $name = 'payment_orders';
    private $detail;

    public function __construct(Payment $model, CashFlow $detail) {
        $this->model = $model;
        $this->detail = $detail;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('order_no',function($model){
                return $model->order->no;
            })
            ->addColumn('total',function($model){
                return $model->order->total;
            })
            ->addColumn('payment',function($model){
                return $model->detail->sum('value');
            })
            ->addColumn('status',function($model){
                return $model->detail->sum('value') >= $model->order->total ? '<label class="label label-success">paid</label>'
                    : '<label class="label label-warning">unpaid</label>';
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->where('type','order')
            ->make(true);
    }

    public function datatablesDetail($id) {
        return Datatables::eloquent($this->detail->query())
            ->addColumn('account_name',function($model){
                return $model->account_code->name;
            })
            ->addColumn('debit',function($model){
                return $model->value < 0 ? 0 : $model->value;
            })
            ->addColumn('credit', function ($model){
                return $model->value > 0 ? 0 : $model->value;
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.payment_detail_order')
            ->where('payment_id',$id)
            ->make(true);
    }
}