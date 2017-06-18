<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\CashFlow;
use App\Models\Order;
use App\Models\Payment;
use Entrust;
use Datatables;

class PaymentOrderService extends Service {

    protected $model;
    protected $name = 'payment_orders';
    private $detail;

    public function __construct(Order $model, CashFlow $detail) {
        $this->model = $model;
        $this->detail = $detail;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('order_no',function($model){
                return $model->no;
            })
            ->addColumn('total',function($model){
                return number_format($model->total);
            })
            ->addColumn('supplier',function($model){
                return $model->supplier->name;
            })
            ->addColumn('payment',function($model){
                return number_format($model->payment->total);
            })
            ->addColumn('status',function($model){
                return $model->payment->total >= $model->total ? '<label class="label label-success">paid</label>'
                    : '<label class="label label-warning">unpaid</label>';
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('id', 'DESC')
            ->where(function ($model) {
                $model->where('payment_method_id', 2);
                if ($supplier_id = request()->input('supplier_id')) {
                    $model->where('supplier_id', $supplier_id);
                }
            })
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
            ->editColumn('debit', function ($model){
                return number_format($model->debit);
            })
            ->editColumn('credit', function ($model){
                return number_format($model->credit);
            })
            ->addColumn('action','actions.payment_detail_order')
            ->where('payment_id',$id)
            ->where('account_code_id','!=','1000.01')
            ->orderBy('id')
            ->make(true);
    }
}