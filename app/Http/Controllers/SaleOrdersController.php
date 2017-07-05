<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SaleOrderService;
use App\Services\SaleService;
use App\Services\ShopService;
use Illuminate\Http\Request;
use PDF;

class SaleOrdersController extends Controller {
    private $page = 'sale_orders';
    private $service;
    private $customer;
    private $product;
    private $shop;

    public function __construct(
        SaleOrderService $service,
        CustomerService $customer,
        ProductService $product,
        ShopService $shop) {

        $this->service  = $service;
        $this->customer = $customer;
        $this->product  = $product;
        $this->shop = $shop;
    }

    public function index() {
        if (request()->ajax()) {
            return $this->service->datatables();
        }
        $data           = $this->service->meta();
        $data['shops']  = $this->shop->all();
        return view('pages.' . $this->page . '.index', $data);
    }

    public function show($id) {
        return view('pages.' . $this->page . '.show', $this->service->find($id));
    }

    public function create() {
        $data                      = $this->service->meta();
        $auto_number               = auto_number_sales();
        $data['auto_number_sales'] = $auto_number;
        $data['transactions']      = session($auto_number);
        $data['total']             = number_format(collect($data['transactions'])->sum('subtotal'));
        $data['shops']             = $this->shop->all();
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
            return abs($val->qty) * ($val->selling_price - $val->disc) * $val->attribute;
        });

        $data['id']     = $id;
        $data['model']  = $model;
        $data['total']  = $total;
        $data['disc']   = $model->disc ?: 0;
        $data['charge'] = $model->cash - $total;
        $data['shops']  = $this->shop->all();
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

    public function viewTempPODetail($no) {
        return session($no);
    }

    public function addTempPODetail(Request $request) {
        $sessions       = session()->has($request->no) ? session($request->no) : [];
        $product        = $this->product->find($request->product_id);
        $selling_price  = $request->selling_price;
        $purchase_price = $product->purchase_price_default;
        $disc           = $product->product_discount ? $product->product_discount->amount : 0;
        $desc           = $request->desc ? ' - '. $request->desc : '';

        $id = md5(time());
        $sessions[ $id ] = [
            'id'             => $id,
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name.$desc,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'desc'           => $request->desc,
            'disc'           => $disc,
            'selling_price'  => $selling_price,
            'purchase_price' => $purchase_price,
            'subtotal'       => $request->qty * ($selling_price - $disc) * $request->attribute
        ];

        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no . '.' . $request->id);
        return array_values($this->viewTempPODetail($request->no));
    }

    public function viewPODetail($no) {
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });

        $transactions = $PO->transactions->map(function ($val, $key) {
            $disc = $val->disc ?: 0;
            $qty  = abs($val->qty);
            $desc = $val->desc ? ' - '. $val->desc : '';
            return [
                'id'             => $val->id,
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name.$desc,
                'attribute'      => $val->attribute,
                'units'          => $val->units,
                'qty'            => $qty,
                'disc'           => $disc,
                'desc'           => $val->desc,
                'selling_price'  => $val->selling_price,
                'purchase_price' => $val->purchase_price,
                'subtotal'       => $qty * ($val->selling_price - $disc) * $val->attribute
            ];
        });

        return $transactions->keyBy('id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no             = $request->no;
        $transactions   = $this->viewPODetail($no);
        $product        = $this->product->find($request->product_id);
        $selling_price  = $request->selling_price;
        $purchase_price = $product->purchase_price_default;
        $disc           = $product->product_discount ? $product->product_discount->amount : 0;
        $desc           = $request->desc ? ' - '. $request->desc : '';
        $key = md5(time());
        $transactions[ $key ] = [
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name.$desc,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'desc'           => $request->desc,
            'disc'           => $disc,
            'selling_price'  => $selling_price,
            'purchase_price' => $purchase_price,
            'subtotal'       => $request->qty * ($selling_price - $disc) * $request->attribute
        ];

        $PO           = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $param        = $transactions[ $key ];
        $param['qty'] = $param['qty'] * -1;
        $PO->transactions()->create($param);

        return array_values($this->viewPODetail($no));
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $PO->transactions()->where('id', $request->id)->delete();
        return array_values($this->viewPODetail($no));
    }

    public function printInvoice($no) {
        $data = [
            'sale' => $this->service->find($no)
        ];

        //return view('pages.sale_orders.print-invoice', $data);
        $pdf = PDF::loadView('pages.sale_orders.print-invoice', $data);
        $pdf->setPaper(array(0, 0, 684.00, 792.00));
        return @$pdf->stream('invoice.pdf');
    }

    public function printDo($no) {
        $data = [
            'sale' => $this->service->find($no)
        ];

        $pdf = PDF::loadView('pages.sale_orders.print-do', $data);
        $pdf->setPaper(array(0, 0, 684.00, 792.00));
        return @$pdf->stream('do.pdf');
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
                  'name' => $val->product->name.' - '.$val->desc,
                  'product_id' => $val->product_id,
                  'qty' => abs($val->qty)
              ];
            })->toArray();
        }
        return [];
    }
}
