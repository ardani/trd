<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Entrust;
use Datatables;

class PurchaseOrderService extends Service {

    protected $model;
    protected $name = 'purchase_orders';

    public function __construct(PurchaseOrder $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {

        return Datatables::eloquent($this->model->query())
            ->addColumn('customer',function ($model) {
                return $model->customer->name;
            })
            ->addColumn('payment_info',function ($model) {
                return view('pages.purchase_orders.info',compact('model'));
            })
            ->addColumn('state',function ($model) {
                return $model->purchase_order_state->state->name;
            })
            ->editColumn('cash',function ($model) {
                return number_format($model->cash);
            })
            ->editColumn('disc',function ($model) {
                return number_format($model->disc);
            })
            ->editColumn('total',function ($model) {
                return number_format($model->total);
            })
            ->editColumn('created_at',function ($model) {
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('id','Desc')
            ->make(true);
    }

    public function store($data) {
        $model = $this->model->firstOrNew(['no' => $data['no']]);
        $model->no = $data['no'];
        $model->customer_id = $data['customer_id'];
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $model->created_at = $created_at;
        $model->cash = $data['cash'];
        $model->cashier_id = auth()->id();
        if (request()->has('payment_method_id')) {
            $model->payment_method_id = 2;
            $model->paid_until_at = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }
        $model->disc = request('disc',0);
        $model->save();
        $model->purchase_order_state()->firstOrCreate(['state_id' => 1]);
        $sessions = session($data['no']);
        $model->transactions()->delete();
        foreach ($sessions as $session) {
            $model->transactions()->create([
                'selling_price' => $session['selling_price'],
                'purchase_price' => $session['purchase_price'],
                'disc' => $session['disc'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty']
            ]);
        }
        return clear_nota($data['no']);
    }
}