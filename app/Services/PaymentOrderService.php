<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\AccountCode;
use App\Models\CashFlow;
use App\Models\Order;
use App\Models\Payment;
use Entrust;
use Datatables;

class PaymentOrderService extends Service {

    protected $model;
    protected $name = 'payment_orders';
    private $detail;
    private $account;

    public function __construct(Order $model, CashFlow $detail, AccountCode $accountCode) {
        $this->model = $model;
        $this->detail = $detail;
        $this->account = $accountCode;
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

    private function listAccount() {
        return $this->account->where(['type' => 1,'parent' => 0])
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function datatablesDetail($id) {
        return Datatables::eloquent($this->detail->query())
            ->addColumn('account_name',function($model){
                return $model->account_code->name;
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('amount', function ($model){
                return number_format(abs($model->debit-$model->credit));
            })
            ->addColumn('action', function ($model) {
                $data = [
                    'id' => $model->id,
                    'payment' => $model->payment,
                    'account_code_id' => $model->account_code_id
                ];
                return view('actions.payment_detail_order', $data);
            })
            ->where('payment_id',$id)
            ->whereIn('account_code_id',$this->listAccount())
            ->orderBy('id')
            ->make(true);
    }
}