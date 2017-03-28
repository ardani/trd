<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SaleOrderService;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleOrdersController extends Controller {
    private $page = 'sale_orders';
    private $service;
    private $customer;
    private $product;

    public function __construct(SaleOrderService $service,
                                CustomerService $customer,
                                ProductService $product) {
        $this->service  = $service;
        $this->customer = $customer;
        $this->product  = $product;
    }

    public function index() {
        if (request()->ajax()) {
            return $this->service->datatables();
        }

        return view('pages.' . $this->page . '.index', $this->service->meta());
    }

    public function show($id) {
        return view('pages.' . $this->page . '.show', $this->service->find($id));
    }

    public function create() {
        $data                      = $this->service->meta();
        $auto_number               = auto_number_sales();
        $data['auto_number_sales'] = $auto_number;
        $data['transactions']      = session($auto_number);

        return view('pages.' . $this->page . '.create', $data);
    }

    public function store(Request $request) {
        $data = $request->all();

        return $this->service->store($data);
    }

    public function edit($id) {
        $model          = $this->service->find($id);
        $data           = $this->service->meta();
        $total          = $model->transactions->sum(function ($val) {
            return abs($val->qty) * ($val->selling_price - $val->disc);
        });
        $data['id']     = $id;
        $data['model']  = $model;
        $data['total']  = $total;
        $data['charge'] = $model->cash - $total;

        return view('pages.' . $this->page . '.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $this->service->update($data, $id);

        return redirect()->back()->with('message', 'Update Success');
    }

    public function delete($id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }
    // create PO
    public function viewTempPODetail($no) {
        return session($no);
    }

    public function addTempPODetail(Request $request) {
        $sessions       = session()->has($request->no) ? session($request->no) : [];
        $product        = $this->product->find($request->product_id);
        $selling_price  = $product->selling_price_default;
        $purchase_price = $product->purchase_price_default;
        $disc           = $product->product_discount ? $product->product_discount->amount : 0;

        if ($selling_price_customer = $product->product_price()
            ->where('customer_type_id', $request->customer_type_id)->first()
        ) {
            $selling_price  = $selling_price_customer->selling_price;
            $purchase_price = $selling_price_customer->purchase_price;
        }

        if (array_key_exists($request->product_id, $sessions)) {
            if (request()->has('is_edit')) {
                $sessions[ $request->product_id ]['qty']      = $request->qty;
                $sessions[ $request->product_id ]['subtotal'] = $request->qty * ($selling_price - $disc);
            }
            else {
                $sessions[ $request->product_id ]['qty'] += $request->qty;
                $sessions[ $request->product_id ]['subtotal'] = $sessions[ $request->product_id ]['qty'] * ($selling_price - $disc);
            }
        }
        else {
            $sessions[ $product->id ] = [
                'product_id'     => $product->id,
                'code'           => $product->code,
                'name'           => $product->name,
                'attribute'      => $request->units,
                'qty'            => $request->qty,
                'disc'           => $disc,
                'selling_price'  => $selling_price,
                'purchase_price' => $purchase_price,
                'subtotal'       => $request->qty * ($selling_price - $disc)
            ];
        }
        session([$request->no => $sessions]);

        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no . '.' . $request->product_id);

        return array_values($this->viewTempPODetail($request->no));
    }

    // edit PO
    public function viewPODetail($no) {
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });

        $transactions = $PO->transactions->map(function ($val, $key) {
            $disc = $val->disc ?: 0;
            $qty  = abs($val->qty);

            return [
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'attribute'      => $val->attribute,
                'qty'            => $qty,
                'disc'           => $disc,
                'selling_price'  => $val->selling_price,
                'purchase_price' => $val->purchase_price,
                'subtotal'       => $qty * ($val->selling_price - $disc)
            ];
        });

        return $transactions->keyBy('product_id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no             = $request->no;
        $transactions   = $this->viewPODetail($no);
        $product        = $this->product->find($request->product_id);
        $selling_price  = $product->selling_price_default;
        $purchase_price = $product->purchase_price_default;
        $disc           = $product->product_discount ? $product->product_discount->amount : 0;

        if ($selling_price_customer = $product->product_price()
            ->where('customer_type_id', $request->customer_type_id)->first()
        ) {
            $selling_price  = $selling_price_customer->selling_price;
            $purchase_price = $selling_price_customer->purchase_price;
        }

        if (array_key_exists($request->product_id, $transactions)) {
            if (request()->has('is_edit')) {
                $transactions[ $request->product_id ]['qty']      = $request->qty;
                $transactions[ $request->product_id ]['subtotal'] = $request->qty * ($selling_price-$disc);
            }
            else {
                $transactions[ $request->product_id ]['qty'] += $request->qty;
                $transactions[ $request->product_id ]['subtotal'] = $transactions[ $request->product_id ]['qty'] * ($selling_price - $disc);
            }
        }
        else {
            $transactions[ $product->id ] = [
                'product_id'     => $product->id,
                'code'           => $product->code,
                'name'           => $product->name,
                'attribute'      => $request->units,
                'qty'            => $request->qty,
                'disc'           => $disc,
                'selling_price'  => $selling_price,
                'purchase_price' => $purchase_price,
                'subtotal'       => $request->qty * ($selling_price - $disc)
            ];
        }

        $PO           = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $param        = $transactions[ $product->id ];
        $param['qty'] = $param['qty'] * -1;
        $PO->transactions()->updateOrCreate(['product_id' => $product->id], $param);

        return array_values($transactions);
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });

        $PO->transactions()->where('product_id', $request->product_id)->delete();

        return array_values($this->viewPODetail($no));
    }

    public function load() {
        if ($q = request()->input('q')) {
            $where =  function($query) use ($q){
                $query->whereRaw('(no like "%'.$q.'%")');
            };
            $sale = $this->service->filter($where,20);
            return $sale->map(function($val,$key) {
                return [
                    'value' => $val->id,
                    'text' => $val->no.' - '.$val->customer->name,
                    'data' => [
                        'customer' => $val->customer->name,
                    ]
                ];
            })->toArray();
        }
        return [];
    }

    public function detail() {
        if ($q = request()->input('id')) {
            $sale = $this->service->find($q);
            return $sale->transactions->map(function($val){
              return [
                  'code' => $val->product->code,
                  'name' => $val->product->name,
                  'product_id' => $val->product_id,
                  'qty' => abs($val->qty)
              ];
            })->toArray();
        }
        return [];
    }
}
