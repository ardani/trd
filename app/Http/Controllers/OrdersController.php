<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private $page = 'orders';
    private $service;
    private $supplier;
    private $product;

    public function __construct(OrderService $service, SupplierService $supplier, ProductService $product) {
        $this->service = $service;
        $this->supplier = $supplier;
        $this->product = $product;
    }

    public function index() {
        if (request()->ajax()) {
            return $this->service->datatables();
        }

        return view('pages.'.$this->page.'.index',$this->service->meta());
    }

    public function show($id) {
        return view('pages.'.$this->page.'.show',$this->service->find($id));
    }

    public function create() {
        $data = $this->service->meta();
        $auto_number = auto_number_orders();
        $data['auto_number_sales'] = $auto_number;
        $data['transactions'] = session($auto_number);
        $total = 0;
        if (session($auto_number)) {
            $total = collect(session($auto_number))->sum(function($val){
                return $val['purchase_price'] * $val['qty'] * $val['attribute'];
            });
        }
        $data['total'] = $total;
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        return $this->service->store($data);
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $total = $model->transactions->sum(function ($val){
            return abs($val->qty) * ($val->purchase_price) * $val->attribute;
        });
        $data['id'] = $id;
        $data['model'] = $model;
        $data['total'] = $total;
        $data['charge'] = $model->cash - $total;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
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
        $sessions = session()->has($request->no) ? session($request->no) : [];
        $product = $this->product->find($request->product_id);
        $purchase_price = $request->purchase_price;
        $selling_price = $request->selling_price;

        $id = md5(time());
        $sessions[ $id ] = [
            'id'             => $id,
            'product_id'     => $request->product_id,
            'code'           => $product->code,
            'name'           => $product->name,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'purchase_price' => $purchase_price,
            'selling_price'  => $selling_price,
            'subtotal'       => $request->qty * $purchase_price * $request->attribute
        ];

        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no.'.'.$request->id);
        return array_values($this->viewTempPODetail($request->no));
    }

    // edit PO
    public function viewPODetail($no) {
        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $transactions = $PO->transactions->map(function($val,$key){
            $qty = $val->qty;
            return  [
                'id'             => $val->id,
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'qty'            => $qty,
                'purchase_price' => $val->purchase_price,
                'selling_price'  => $val->selling_price,
                'attribute'      => $val->attribute,
                'units'          => $val->units,
                'subtotal'       => $qty * ($val->purchase_price) * $val->attribute
            ];
        });

        return $transactions->keyBy('id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no = $request->no;
        $transactions = $this->viewPODetail($no);
        $product = $this->product->find($request->product_id);
        $purchase_price = $product->purchase_price;
        $selling_price = $product->selling_price;

        $key = md5(time());
        $transactions[ $key ] = [
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'purchase_price' => $purchase_price,
            'selling_price'  => $selling_price,
            'subtotal'       => $request->qty * ($purchase_price) * $request->attribute
        ];

        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $PO->transactions()->create($transactions[$key]);
        return array_values($this->viewPODetail($no));
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function($query) use ($no) {
            $query->where('no',$no);
        });

        $PO->transactions()->where('product_id',$request->product_id)->delete();
        return array_values($this->viewPODetail($no));
    }

    public function printInvoice($no) {
        $data = [
            'order' => $this->service->find($no)
        ];
        return view('pages.orders.print-invoice', $data);
    }

    public function load() {
        $q = request()->input('q');
        if (!$q) {
            return [];
        }
        $where =  function($query) use ($q){
            $query->whereRaw('(no like "%'.$q.'%")');
        };
        $sale = $this->service->filter($where,20);
        return $sale->map(function($val,$key) {
            return [
                'value' => $val->id,
                'text' => $val->no.' - '.$val->supplier->name,
                'data' => [
                    'supplier' => $val->supplier->name,
                ]
            ];
        })->toArray();
    }

    public function detail() {
        $q = request()->input('id');
        if (!$q) {
            return [];
        }

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
}
