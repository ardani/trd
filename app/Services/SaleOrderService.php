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
use App\Models\Production;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Entrust;
use Datatables;

class SaleOrderService extends Service {

    protected $model;
    protected $name = 'sale_orders';
    private $production;
    private $payment;

    public function __construct(SaleOrder $model, Production $production, Payment $payment) {
        $this->model = $model;
        $this->production = $production;
        $this->payment = $payment;
    }

    public function datatables($param = array()) {

        return Datatables::eloquent($this->model->query())
            ->addColumn('customer',function ($model) {
                return $model->customer->name;
            })
            ->addColumn('payment_info',function ($model) {
                return view('pages.sale_orders.info',compact('model'));
            })
            ->addColumn('state',function ($model) {
                return $model->sale_order_state->state->name;
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
        $model->note = $data['note'];
        $model->cash = $data['cash'];
        $model->cashier_id = auth()->id();

        if (request()->has('payment_method_id')) {
            $model->payment_method_id = 2;
            $model->paid_until_at = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }
        $model->disc = request('disc',0);
        $model->save();
        $total = $model->total > $data['cash'] ? $data['cash'] : $model->total;
        $this->savePayment($model, $total);
        $model->sale_order_state()->firstOrCreate(['state_id' => 1]);
        $sessions = session($data['no']);
        $model->transactions()->delete();
        foreach ($sessions as $session) {
            $model->transactions()->create([
                'selling_price' => $session['selling_price'],
                'purchase_price' => $session['purchase_price'],
                'attribute' => $session['attribute'],
                'units' => $session['units'],
                'disc' => $session['disc'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty'] * -1
            ]);
        }
        return clear_nota($data['no']);
    }

    public function delete($id)
    {
        $note = request()->input('note','');
        $this->model->find($id)->update(['note' => $note]);
        return parent::delete($id);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $model->fill($data);
        return $model->save();
    }

    private function savePayment($model, $cash) {
        $payment = $this->payment->firstOrCreate([
            'cashier_id' => auth()->user()->id,
            'type' => 'sale',
            'ref_id' => $model->id
        ]);
        $payment->detail()->updateOrCreate([
            'value' => $cash,
            'account_code_id' => setting('account.sale'),
            'is_direct' => 1
        ],['value' => $cash]);
    }
}