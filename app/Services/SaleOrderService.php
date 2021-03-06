<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

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
                return $model->state->name;
            })
            ->addColumn('shop',function ($model) {
                return $model->shop_id ? $model->shop->name : '';
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
            ->addColumn('afterdisc',function ($model) {
                return number_format($model->total - $model->disc);
            })
            ->addColumn('remain',function($model){
                if ($model->payment_method_id == 2) {
                    $remain = abs($model->total - $model->disc - abs($model->payment->total));
                    return number_format(floor($remain));
                }
                return '-';
            })
            ->editColumn('created_at',function ($model) {
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action',function ($model) {
                $data = [
                    'id' => $model->id,
                    'status_id' => $model->state_id
                ];
                return view('actions.'.$this->name, $data);
            })
            ->orderBy('id','Desc')
            ->where(function ($model) {
                if ($customer_id = request()->input('customer_id')) {
                    $model->where('customer_id', $customer_id);
                }

                if ($shop_id = request()->input('shop_id')) {
                    $model->where('shop_id', $shop_id);
                }

                if ($date_untils = date_until(request()->input('date_until'))) {
                    $model->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }

                if ($state_id = request()->input('state_id')) {
                    $model->where('state_id', $state_id);
                }
            })
            ->make(true);
    }

    public function store($data) {
        $no = auto_number_sales();
        $model = $this->model->firstOrNew(['no' => $no]);
        $model->no = $no;
        $model->customer_id = $data['customer_id'];
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $model->created_at = $created_at;
        $model->note = $data['note'];
        $model->cash = $data['cash'];
        $model->shop_id = $data['shop_id'];
        $model->cashier_id = auth()->id();

        if (request()->has('payment_method_id')) {
            $model->payment_method_id = 2;
            $model->paid_until_at = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }

        $model->disc = request('disc',0);
        $model->save();

        $sessions = session($data['no']);
        $model->transactions()->delete();
        $total = 0;
        foreach ($sessions as $session) {
            $total+= ($session['selling_price']-$session['disc']) * $session['qty'] * $session['attribute'];
            $model->transactions()->create([
                'selling_price' => $session['selling_price'],
                'purchase_price' => $session['purchase_price'],
                'attribute' => $session['attribute'],
                'units' => $session['units'],
                'disc' => $session['disc'],
                'desc' => $session['desc'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty'] * -1,
                'created_at' => $created_at
            ]);
        }
        return $this->savePayment($model, $total);
    }

    public function delete($id) {
        $note = request()->input('note','');
        $this->model->find($id)->update(['note' => $note]);
        return parent::delete($id);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $data['created_at'] = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        if (isset($data['payment_method_id']) && $data['payment_method_id'] == 2) {
            $data['paid_until_at'] = Carbon::createFromFormat('d/m/Y',$data['paid_until_at'])->format('Y-m-d');
        }

        $model->fill($data);
        $model->save();
        return $this->savePayment($model, $model->total);;
    }

    private function savePayment($model, $total) {
        /*5000.01 - pembelian
        4000.01 - penjualan
        1000.01 - kas kecil
        1100.01 - piutang dagang
        2000.01 - hutang dagang*/
        $payment = $this->payment->firstOrCreate([
            'cashier_id' => auth()->user()->id,
            'type' => 'sale',
            'ref_id' => $model->id
        ]);

        $payment->detail()->delete();
        $account_code = $model->payment_method_id == 1 ? '1000.01' : '1100.01';
        $payment->detail()->create([
            'debit' => $total,
            'account_code_id' => $account_code,
            'note' => 'sale no ' . $model->no
        ]);
        // penjualan
        $payment->detail()->create([
            'credit' => $total,
            'account_code_id' => '4000.01',
            'note' => 'sale no ' . $model->no
        ]);

        if ($model->cash && $model->payment_method_id == 2) {
            $cash = $payment->detail()->create([
                'credit' => $model->cash,
                'account_code_id' => '1100.02',
                'note' => 'dp sale no ' . $model->no
            ]);

            $payment->detail()->create([
                'debit' => $model->cash,
                'account_code_id' => '1000.01',
                'note' => 'dp sale no ' . $model->no,
                'from_to_id' => $cash->id
            ]);
        }
    }

    public function getData($shop_id, $customer_id, $date) {
        $result = $this->model->where(function ($model) use ($shop_id, $customer_id, $date) {
            if ($customer_id) {
                $model->where('customer_id', $customer_id);
            }

            if ($shop_id) {
                $model->where('shop_id', $shop_id);
            }

            if ($date_untils = date_until($date)) {
                $model->where('created_at','>=',$date_untils[0])
                    ->where('created_at','<=',$date_untils[1]);
            }
        })->get();

        return $result;
    }
}