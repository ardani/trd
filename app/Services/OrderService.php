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
            ->orderBy('id','DESC')
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
        $model->invoice_no = $data['invoice_no'];
        $model->delivery_order_no = $data['delivery_order_no'];

        if (request()->has('payment_method_id')) {
            $model->payment_method_id = 2;
            $model->paid_until_at = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }
        $model->save();
        $sessions = session($data['no']);
        $model->transactions()->delete();
        $total = 0;
        foreach ($sessions as $session) {
            $total += $session['purchase_price'] * $session['qty'] * $session['attribute'];
            $model->transactions()->create([
                'purchase_price' => $session['purchase_price'],
                'selling_price' => $session['selling_price'],
                'attribute' => $session['attribute'],
                'units' => $session['units'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty'],
                'attribute' => $session['attribute']
            ]);
            $this->updateSellingPrice($session['product_id'], $session['selling_price'], $session['purchase_price']);
        }

        $this->savePayment($model, $total);
        return clear_nota($data['no']);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $data['created_at'] = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        if (isset($data['payment_method_id']) && $data['payment_method_id'] == 2) {
            $data['paid_until_at'] = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }

        $model->fill($data);
        $model->save();
        return $this->savePayment($model, $model->total);
    }

    private function savePayment($model, $total) {
        /*5000.01 - pembelian
        4000.01 - penjualan
        1000.01 - kas kecil
        1100.01 - piutang dagang
        2000.01 - hutang dagang*/

        $payment = $this->payment->firstOrCreate([
            'cashier_id' => auth()->user()->id,
            'type' => 'order',
            'ref_id' => $model->id
        ]);

        $payment->detail()->delete();
        $account_code = $model->payment_method_id == 1 ? '1000.01' : '2000.01';
        $payment->detail()->create([
            'credit' => $total,
            'account_code_id' => $account_code,
            'note' => 'order no ' . $model->no
        ]);
        // pembelian
        $payment->detail()->create([
            'debit' => $total,
            'account_code_id' => '5000.01',
            'note' => 'order no ' . $model->no
        ]);

        if ($model->cash &&  $model->payment_method_id == 2) {
            $cash = $payment->detail()->create([
                'debit' => $model->cash,
                'account_code_id' => '2000.02',
                'note' => 'dp order no ' . $model->no
            ]);
            $payment->detail()->create([
                'credit' => $model->cash,
                'account_code_id' => '1000.01',
                'note' => 'dp order no ' . $model->no,
                'from_to_id' => $cash->id
            ]);
        }
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