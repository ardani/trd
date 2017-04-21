<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\RequestProductService;
use Illuminate\Http\Request;

class RequestProductsController extends Controller {
    private $page = 'request_products';
    private $service;
    private $product;

    public function __construct(RequestProductService $service, ProductService $product) {
        $this->service  = $service;
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
        $auto_number               = auto_number_request_product();
        $data['auto_number'] = $auto_number;
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
        $data['id']     = $id;
        $data['model']  = $model;
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

        if (array_key_exists($request->product_id, $sessions)) {
            if (request()->has('is_edit')) {
                $sessions[ $request->product_id ]['qty'] = $request->qty;
            }
            else {
                $sessions[ $request->product_id ]['qty'] += $request->qty;
            }
        }
        else {
            $sessions[ $product->id ] = [
                'product_id'     => $product->id,
                'code'           => $product->code,
                'name'           => $product->name,
                'attribute'      => $request->attribute,
                'units'          => $request->units,
                'qty'            => $request->qty,
                'selling_price'  => $selling_price,
                'purchase_price' => $purchase_price,
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
            $qty  = abs($val->qty);
            return [
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'attribute'      => $val->attribute,
                'units'          => $val->units,
                'qty'            => $qty,
                'selling_price'  => $val->selling_price,
                'purchase_price' => $val->purchase_price
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

        if (array_key_exists($request->product_id, $transactions)) {
            if (request()->has('is_edit')) {
                $transactions[ $request->product_id ]['qty'] = $request->qty;
            }
            else {
                $transactions[ $request->product_id ]['qty'] += $request->qty;
            }
        }
        else {
            $transactions[ $product->id ] = [
                'product_id'     => $product->id,
                'code'           => $product->code,
                'name'           => $product->name,
                'attribute'      => $request->attribute,
                'units'          => $request->units,
                'qty'            => $request->qty,
                'selling_price'  => $selling_price,
                'purchase_price' => $purchase_price
            ];
        }

        $PO           = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $param        = $transactions[ $product->id ];
        $param['qty'] = $param['qty'];
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
}
