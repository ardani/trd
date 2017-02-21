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
                return $val['purchase_price']*$val['qty'];
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
            return abs($val->qty) * ($val->purchase_price);
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

        if (array_key_exists($request->product_id,$sessions)) {
            if (request()->has('is_edit')) {
                $sessions[$request->product_id]['qty'] = $request->qty;
                $sessions[$request->product_id]['subtotal'] = $request->qty * $purchase_price;
            } else {
                $sessions[ $request->product_id ]['qty'] += $request->qty;
                $sessions[$request->product_id]['subtotal'] = $sessions[$request->product_id]['qty'] * $purchase_price;
            }
        } else {
            $sessions[ $product->id ] = [
                'product_id'     => $request->product_id,
                'code'           => $product->code,
                'name'           => $product->name,
                'qty'            => $request->qty,
                'purchase_price' => $purchase_price,
                'subtotal'       => $request->qty * $purchase_price
            ];
        }
        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no.'.'.$request->product_id);
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
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'qty'            => $qty,
                'purchase_price' => $val->purchase_price,
                'subtotal'       => $qty * ($val->purchase_price)
            ];
        });

        return $transactions->keyBy('product_id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no = $request->no;
        $transactions = $this->viewPODetail($no);
        $product = $this->product->find($request->product_id);
        $purchase_price = $product->purchase_price;

        if (array_key_exists($request->product_id,$transactions)) {
            if (request()->has('is_edit')) {
                $transactions[$request->product_id]['qty'] = $request->qty;
                $transactions[$request->product_id]['subtotal'] = $request->qty * $purchase_price;
            } else {
                $transactions[$request->product_id]['qty'] += $request->qty;
                $transactions[$request->product_id]['subtotal'] = $transactions[$request->product_id]['qty'] * $purchase_price;
            }
        } else {
            $transactions[ $product->id ] = [
                'product_id'     => $product->id,
                'code'           => $product->code,
                'name'           => $product->name,
                'qty'            => $request->qty,
                'purchase_price' => $purchase_price,
                'subtotal'       => $request->qty * ($purchase_price)
            ];
        }

        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $PO->transactions()->updateOrCreate(['product_id' => $product->id],$transactions[ $product->id ]);

        return array_values($transactions);
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function($query) use ($no) {
            $query->where('no',$no);
        });

        $PO->transactions()->where('product_id',$request->product_id)->delete();
        return array_values($this->viewPODetail($no));
    }
}
