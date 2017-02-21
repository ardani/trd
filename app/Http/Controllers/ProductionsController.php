<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Services\ProductionService;
use App\Services\ProductService;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class ProductionsController extends Controller
{
    private $page = 'productions';
    private $service;
    private $po;
    private $product;

    public function __construct(ProductionService $service, PurchaseOrderService $po, ProductService $product) {
        $this->service = $service;
        $this->po = $po;
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

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['states'] = State::get();
        $data['id'] = $id;
        $data['model'] = $model;
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

    // edit PO
    public function viewPRDetail($no) {
        $PR = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $transactions = $PR->transactions->map(function($val,$key){
            $qty = abs($val->qty);
            return  [
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'qty'            => $qty
            ];
        });

        return $transactions->keyBy('product_id')->toArray();
    }

    public function addPRDetail(Request $request) {
        $no = $request->no;
        $transactions = $this->viewPRDetail($no);
        $product = $this->product->find($request->product_id);

        if (array_key_exists($request->product_id,$transactions)) {
            if (request()->has('is_edit')) {
                $transactions[$request->product_id]['qty'] = $request->qty;
            } else {
                $transactions[$request->product_id]['qty'] += $request->qty;
            }
        } else {
            $transactions[ $product->id ] = [
                'product_id'     => $product->id,
                'selling_price'  => $product->selling_price_default,
                'purchase_price'  => $product->purchase_price_default,
                'code'           => $product->code,
                'name'           => $product->name,
                'qty'            => $request->qty
            ];
        }

        $PR = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });
        $param = $transactions[$product->id];
        $param['qty'] = $param['qty'] * -1;

        $PR->transactions()->updateOrCreate(['product_id' => $product->id],$param);

        return array_values($transactions);
    }

    public function deletePRDetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function($query) use ($no) {
            $query->where('no',$no);
        });

        $PO->transactions()->where('product_id',$request->product_id)->delete();
        return array_values($this->viewPRDetail($no));
    }
}
