<?php

namespace App\Http\Controllers;

use App\Services\CashFlowService;
use App\Services\PaymentSaleService;
use Illuminate\Http\Request;
class PaymentSalesController extends Controller
{
    private $page = 'payment_sales';
    private $service;
    private $service_detail;

    public function __construct(PaymentSaleService $service, CashFlowService $detail) {
        $this->service = $service;
        $this->service_detail = $detail;
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

    public function create($sale_id) {
        $data = $this->service->meta();
        $data['sale_id'] = $sale_id;
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $sale = $this->service->find($data['sale_id']);
        $cash = $this->service_detail->store([
            'account_code_id' => '1000.01',
            'debit' => $data['credit'],
            'payment_id' => $sale->payment->id,
            'note' => 'installment sale ' . $sale->no
        ]);
        $data['payment_id'] = $sale->payment->id;
        $data['from_to_id'] = $cash->id;
        $this->service_detail->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($sale_id, $id) {
        $model = $this->service_detail->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['sale_id'] = $sale_id;
        $data['model'] = $model;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update($sale_id, $id) {
        $data = request()->all();
        $this->service_detail->update($data, $id);
        $payment = $this->service_detail->find($id);
        $cash = $this->service_detail->find($payment->from_to_id);
        $cash->debit = $data['credit'];
        $cash->save();
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($sale_id, $id) {
        $deleted = $this->service_detail->delete($id);
        return ['status' => $deleted];
    }

    public function detail($sale_id) {
        $sale = $this->service->find($sale_id);
        if (request()->ajax()) {
            return $this->service->datatablesDetail($sale->payment->id);
        }

        $metas = [
            'name' => 'Payment Detail '.$sale->no,
            'description' => '',
            'sale_id' => $sale_id
        ];
        return view('pages.'.$this->page.'.index_detail',$metas);
    }

    public function printPayment($id) {
        $data['sale'] = $this->service->find($id);
        return view('pages.'.$this->page.'.print-payment', $data);
    }
}
