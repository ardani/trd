<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Entrust;
use Datatables;

class OrderService extends Service {

    protected $model;
    protected $name = 'orders';
    private $payment;
    private $product;

    public function __construct(Order $model, Payment $payment, Product $product) {
        $this->model = $model;
        $this->payment = $payment;
        $this->product = $product;
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
            ->editColumn('total',function($model){
                return number_format($model->total);
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('created_at','DESC')
            ->where(function ($model) {
                if ($supplier_id = request()->input('supplier_id')) {
                    $model->where('supplier_id', $supplier_id);
                }

                if ($date_untils = date_until(request()->input('date_until'))) {
                    $model->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }
            })
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
                'selling_price' => $session['selling_price'],
                'units' => $session['units'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty'],
                'attribute' => $session['attribute']
            ]);
            $this->updateSellingPrice($session['product_id'], $session['selling_price'], $session['purchase_price']);
        }

        return clear_nota($data['no']);
    }

    private function savePayment($model, $cash) {
        $payment = $this->payment->firstOrCreate([
            'cashier_id' => auth()->user()->id,
            'type' => 'order',
            'ref_id' => $model->id
        ]);
        $where = [
            'value' => $cash * -1,
            'account_code_id' => setting('account.order'),
            'is_direct' => 1
        ];
        $payment->detail()->updateOrCreate($where,['value' => $cash * -1]);
    }

    public function delete($id) {
        $note = request()->input('note','');
        $this->model->find($id)->update(['note' => $note]);
        return parent::delete($id);
    }

    private function updateSellingPrice($product_id, $selling_price, $purchase_price) {
        // using average
        $product = $this->product->find($product_id);
        $avg_selling_price = $selling_price;
        $avg_purchase_price = ($product->purchase_price_default + $purchase_price) / 2;
        $product->selling_price_default = round($avg_selling_price);
        $product->purchase_price_default = round($avg_purchase_price);
        $product->save();
    }
}