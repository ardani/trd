<?php

namespace App\Http\Controllers;

use App\Services\CashFlowService;
use App\Services\PaymentOrderService;
use Illuminate\Http\Request;
class PaymentOrdersController extends Controller
{
    private $page = 'payment_orders';
    private $service;
    private $service_detail;

    public function __construct(PaymentOrderService $service, CashFlowService $detail) {
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

    public function create($order_id) {
        $data = $this->service->meta();
        $data['order_id'] = $order_id;
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $order = $this->service->find($data['order_id']);
        $cash = $this->service_detail->store([
            'account_code_id' => '1000.01',
            'credit' => $data['debit'],
            'payment_id' => $order->payment->id,
            'note' => 'installment sale ' . $order->no
        ]);
        $data['from_to_id'] = $cash->id;
        $data['payment_id'] = $order->payment->id;
        $this->service_detail->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($order_id, $id) {
        $model = $this->service_detail->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['order_id'] = $order_id;
        $data['model'] = $model;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update($order_id, $id) {
        $data = request()->all();
        $this->service_detail->update($data, $id);
        $payment = $this->service_detail->find($id);
        $cash = $this->service_detail->find($payment->from_to_id);
        $cash->credit = $data['debit'];
        $cash->save();
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($order_id, $id) {
        $deleted = $this->service_detail->delete($id);
        return ['status' => $deleted];
    }

    public function detail($order_id) {
        $order = $this->service->find($order_id);
        if (request()->ajax()) {
            return $this->service->datatablesDetail($order->payment->id);
        }

        $metas = [
            'name' => 'Payment Detail '.$order->no,
            'description' => '',
            'order_id' => $order_id
        ];
        return view('pages.'.$this->page.'.index_detail',$metas);
    }

    public function printPayment($id) {
        $data['order'] = $order = $this->service->find($id);
        return view('pages.'.$this->page.'.print-payment', $data);
    }
}
