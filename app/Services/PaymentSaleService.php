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
use App\Models\SaleOrder;
use Entrust;
use Datatables;

class PaymentSaleService extends Service {

    protected $model;
    protected $name = 'payment_sales';
    private $detail;

    public function __construct(SaleOrder $model, CashFlow $detail) {
        $this->model = $model;
        $this->detail = $detail;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('sale_no',function($model){
                return $model->no;
            })
            ->addColumn('total',function($model){
                return number_format($model->total);
            })
            ->addColumn('customer',function($model){
                return $model->customer->name;
            })
            ->addColumn('payment',function($model){
                return number_format(abs($model->payment->total));
            })
            ->addColumn('status',function($model){
                return abs($model->payment->total) >= $model->total ? '<label class="label label-success">paid</label>'
                    : '<label class="label label-warning">unpaid</label>';
            })
            ->editColumn('created_at', function ($model){
                return $model->payment->created_at->format('d/m/Y');
            })
            ->addColumn('action',function ($model) {
                $data = [
                    'id' => $model->id,
                    'payment' => $model->payment
                ];
                return view('actions.'.$this->name, $data);
            })
            ->where(function ($model) {
                $model->where('payment_method_id', 2);
                if ($customer_id = request()->input('customer_id')) {
                    $model->where('customer_id', $customer_id);
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
            ->addColumn('action', function ($model) {
                $data = [
                    'id' => $model->id,
                    'payment' => $model->payment,
                    'account_code_id' => $model->account_code_id
                ];
                return view('actions.payment_detail_sale', $data);
            })
            ->where('payment_id',$id)
            ->where('account_code_id','!=','1000.01')
            ->orderBy('id')
            ->make(true);
    }
}