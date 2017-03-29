<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Entrust;
use Datatables;

class OrderService extends Service {

    protected $model;
    protected $name = 'orders';

    public function __construct(Order $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('supplier', function($model){
                return $model->supplier->name;
            })
            ->editColumn('arrive_at',function($model){
                return $model->arrive_at ? $model->arrive_at->format('d/m/Y') : '-';
            })
            ->editColumn('paid_until_at',function($model){
                return $model->paid_until_at ? $model->paid_until_at->format('d/m/Y') : '-';
            })
            ->addColumn('payment_method',function($model){
                return $model->payment_method->name;
            })
            ->editColumn('created_at',function($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('created_at','DESC')
            ->make(true);
    }

    public function store($data) {
        $model = $this->model->firstOrNew(['no' => $data['no']]);
        $model->no = $data['no'];
        $model->supplier_id = $data['supplier_id'];
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $model->created_at = $created_at;
        $model->cash = $data['cash'];
        $model->cashier_id = auth()->id();

        if (request()->has('payment_method_id')) {
            $model->payment_method_id = 2;
            $model->paid_until_at = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }
        $model->save();
        $total = $model->total > $data['cash'] ? $data['cash'] : $model->total;
        $this->savePayment($model, $total);

        $sessions = session($data['no']);
        $model->transactions()->delete();
        foreach ($sessions as $session) {
            $model->transactions()->create([
                'purchase_price' => $session['purchase_price'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty'],
                'attribute' => $session['attribute']
            ]);
        }
        return clear_nota($data['no']);
    }

    private function savePayment($model, $cash) {
        $payment = $this->payment->firstOrCreate([
            'cashier_id' => auth()->user()->id,
            'type' => 'order',
            'ref_id' => $model->id
        ]);

        $payment->detail()->updateOrCreate([
            'value' => $cash * -1,
            'account_code_id' => setting('account.order'),
            'is_direct' => 1
        ],['value' => $cash * -1]);
    }
}