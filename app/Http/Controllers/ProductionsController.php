<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Transaction;
use App\Services\ProductionService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductionsController extends Controller
{
    private $page = 'productions';
    private $service;
    private $product;
    private $transaction;

    public function __construct(ProductionService $service, ProductService $product, Transaction $transaction) {
        $this->service = $service;
        $this->product = $product;
        $this->transaction = $transaction;
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
    public function PRDetail(Request $request)
    {
        $no = $request->no;
        $production_product_id = $request->production_product_id;
        $transactions = $this->viewPRDetail($no, $production_product_id);
        return array_values($transactions);
    }

    private function viewPRDetail($no, $production_product_id = null) {
        $PR = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });
        $transactions = [];
        foreach ($PR->transactions->where('production_product_id', $production_product_id)->all() as $row) {
            $transactions[$row->product->id.$production_product_id] =  [
                'product_id'     => $row->product->id,
                'code'           => $row->product->code,
                'name'           => $row->product->name,
                'attribute'      => $row->attribute,
                'units'          => $row->units,
                'qty'            => abs($row->qty)
            ];
        }
        return $transactions;
    }

    public function addPRDetail(Request $request) {
        $no = $request->no;
        $transactions = $this->viewPRDetail($no, $request->production_product_id);
        $product = $this->product->find($request->product_id);

        if (array_key_exists($request->product_id . $request->production_product_id, $transactions)) {
            if (request()->has('is_edit')) {
                $transactions[$request->product_id . $request->production_product_id]['qty'] = $request->qty;
            } else {
                $transactions[$request->product_id . $request->production_product_id]['qty'] += $request->qty;
            }
        } else {
            $transactions[ $product->id . $request->production_product_id ] = [
                'product_id'     => $product->id,
                'selling_price'  => $product->selling_price_default,
                'purchase_price' => $product->purchase_price_default,
                'code'           => $product->code,
                'name'           => $product->name,
                'attribute'      => $request->attribute,
                'units'          => $request->units,
                'qty'            => $request->qty,
                'production_product_id' => $request->production_product_id
            ];
        }

        $pr = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $param = $transactions[$product->id . $request->production_product_id ];
        $param['qty'] = $param['qty'] * -1;

        $pr->transactions()->updateOrCreate([
            'product_id' => $product->id,
            'production_product_id' => $request->production_product_id
        ], $param);
        return array_values($transactions);
    }

    public function deletePRDetail(Request $request) {
        $no = $request->no;
        $pr = $this->service->where(function($query) use ($no) {
            $query->where('no',$no);
        });

        $pr->transactions()
            ->where('production_product_id',$request->production_product_id)
            ->where('product_id',$request->product_id)
            ->delete();
        return array_values($this->viewPRDetail($no, $request->production_product_id));
    }

    public function finished($id) {
        if ($transaction =  $this->transaction->find($id)) {
            $transaction->update(['status' => 1]);
            return ['status' => 'ok'];
        }
    }

    public function completed($id) {
        if ($production = $this->service->find($id)) {
            $production->sale_order->update(['state_id' => 3, 'cashier_id' => auth()->id()]);
            return redirect()->back()->with('message', 'Update Status Complete Success');
        }
        return redirect()->back()->withErrors('Update Status Failed');
    }

    public function spk($id) {

    }
}
