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
use App\Models\SaleOrder;
use Entrust;
use Datatables;

class PaymentSaleService extends Service {

    protected $model;
    protected $name = 'payment_sales';
    private $detail;
    private $account;

    public function __construct(SaleOrder $model, CashFlow $detail, AccountCode $account) {
        $this->model = $model;
        $this->detail = $detail;
        $this->account = $account;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('sale_no',function($model){
                $shop = $model->shop_id ? '<br>'.'('.$model->shop->name.')' : '';
                return $model->no . $shop;
            })
            ->addColumn('total',function($model){
                return number_format($model->total - $model->disc);
            })
            ->addColumn('customer',function($model){
                return $model->customer->name;
            })
            ->addColumn('payment',function($model){
                return number_format(abs($model->payment->total));
            })
            ->addColumn('remain',function($model){
                $remain = abs($model->total - $model->disc - abs($model->payment->total));
                return number_format(floor($remain));
            })
            ->addColumn('status',function($model){
                return $model->paid_status ? '<label class="label label-success">paid</label>'
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
            ->where(function ($query) {
                $query->where('payment_method_id', 2);
                if ($customer_id = request()->input('customer_id')) {
                    $query->where('customer_id', $customer_id);
                }

                if ($shop_id = request()->input('shop_id')) {
                    $query->where('shop_id', $shop_id);
                }

                if ($date_untils = date_until(request()->input('date_until'))) {
                    $query->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
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
                return view('actions.payment_detail_sale', $data);
            })
            ->where('payment_id',$id)
            ->whereIN('account_code_id', $this->listAccount())
            ->orderBy('id')
            ->make(true);
    }

    public function getData($date, $customer_id) {
        $result = $this->model->where(function ($query) use ($date, $customer_id) {
            $query->where('payment_method_id', 2);
            if ($customer_id) {
                $query->where('customer_id', $customer_id);
            }

            if ($date_untils = date_until($date)) {
                $query->where('created_at','>=',$date_untils[0])
                    ->where('created_at','<=',$date_untils[1]);
            }
        })->get();
        return $result;
    }
}