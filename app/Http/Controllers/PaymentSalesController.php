<?php

namespace App\Http\Controllers;

use App\Services\AccountCodeService;
use App\Services\CashFlowService;
use App\Services\PaymentSaleService;
use App\Services\ShopService;
use Carbon\Carbon;
use Illuminate\Http\Request;
class PaymentSalesController extends Controller
{
    private $page = 'payment_sales';
    private $service;
    private $service_detail;
    private $account;
    private $shop;

    public function __construct(
        PaymentSaleService $service,
        CashFlowService $detail,
        AccountCodeService $account,
        ShopService $shop) {
        $this->service = $service;
        $this->service_detail = $detail;
        $this->account = $account;
        $this->shop = $shop;
    }

    public function index() {
        if (request()->ajax()) {
            return $this->service->datatables();
        }
        $data = $this->service->meta();
        $data['shops'] = $this->shop->all();
        return view('pages.'.$this->page.'.index',$data);
    }

    public function show($id) {
        return view('pages.'.$this->page.'.show',$this->service->find($id));
    }

    public function create($sale_id) {
        $data = $this->service->meta();
        $data['sale_id'] = $sale_id;
        $data['accounts'] = $this->account->filter(['type' => 1,'parent' => 0]);
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $sale = $this->service->find($data['sale_id']);
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $cash = $this->service_detail->store([
            'account_code_id' => '1100.03',
            'credit' => $data['debit'],
            'payment_id' => $sale->payment->id,
            'note' => 'installment sale ' . $sale->no,
            'created_at' => $created_at
        ]);
        $data['note'] = 'installment sale '. $sale->no .' '.$data['note'];
        $data['created_at'] = $created_at;
        $data['payment_id'] = $sale->payment->id;
        $data['from_to_id'] = $cash->id;
        $this->service_detail->store($data);
        return redirect('payment_sales/detail/'. $data['sale_id'])->with('message','Save Success');
    }

    public function edit($sale_id, $id) {
        $model = $this->service_detail->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['sale_id'] = $sale_id;
        $data['model'] = $model;
        $data['accounts'] = $this->account->filter(['type' => 1,'parent' => 0]);
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update($sale_id, $id) {
        $data = request()->all();
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $data['created_at'] = $created_at;
        $this->service_detail->update($data, $id);
        $payment = $this->service_detail->find($id);
        $cash = $this->service_detail->find($payment->from_to_id);
        $cash->credit = $data['debit'];
        $cash->created_at = $created_at;
        $cash->save();
        return redirect('payment_sales/detail/'. $sale_id)->with('message','Update Success');
    }

    public function delete($sale_id, $id) {
        $data = $this->service_detail->find($id);
        $this->service_detail->delete($data->from_to_id);
        $deleted = $this->service_detail->delete($id);
        return ['status' => $deleted];
    }

    public function detail($sale_id) {
        $sale = $this->service->find($sale_id);
        if (request()->ajax()) {
            return $this->service->datatablesDetail($sale->payment->id);
        }

        $metas = [
            'name' => 'Payment Detail '. $sale->no,
            'description' => '',
            'sale_id' => $sale_id
        ];
        return view('pages.'.$this->page.'.index_detail',$metas);
    }

    public function printPayment($id) {
        $data['sale'] = $this->service->find($id);
        return view('pages.'.$this->page.'.print-payment', $data);
    }

    public function doPrint(Request $request) {
        $data['sales'] = $this->service->getData($request->date, $request->supplier_id);
        return view('pages.payment_sales.print', $data);
    }
}
