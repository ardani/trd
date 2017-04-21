<?php

namespace App\Http\Controllers;

use App\Services\CashFlowService;
use App\Services\PaymentOrderService;
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

    public function create($payment_id) {
        $data = $this->service->meta();
        $data['payment_id'] = $payment_id;
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $this->service_detail->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($payment_id, $id) {
        $model = $this->service_detail->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['payment_id'] = $payment_id;
        $data['model'] = $model;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update($payment_id, $id) {
        $data = request()->all();
        $this->service_detail->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($payment_id, $id) {
        $deleted = $this->service_detail->delete($id);
        return ['status' => $deleted];
    }

    public function detail($payment_id) {
        if (request()->ajax()) {
            return $this->service->datatablesDetail($payment_id);
        }
        $payment = $this->service->find($payment_id);
        $metas = [
            'name' => 'Payment Detail '.$payment->sale->no,
            'description' => '',
            'payment_id' => $payment_id,
            'path' => url('payment_sale/detail/'.$payment_id)
        ];
        return view('pages.'.$this->page.'.index_detail',$metas);
    }
}
