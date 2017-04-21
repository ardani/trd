<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SaleOrderService;
use App\Services\SaleService;
use Illuminate\Http\Request;

class InvoicesController extends Controller {
    private $page = 'invoices';
    private $service;
    private $customer;
    private $product;

    public function __construct(SaleOrderService $service,
                                CustomerService $customer,
                                ProductService $product) {
        $this->service  = $service;
        $this->customer = $customer;
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

    public function viewPODetail($no) {
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });

        $transactions = $PO->transactions->map(function ($val, $key) {
            $disc = $val->disc ?: 0;
            $qty  = abs($val->qty);

            return [
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name.' - '.$val->desc,
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

        return $transactions->keyBy('product_id')->toArray();
    }

    public function printInvoice() {

    }

    public function printDeliveryOrder() {

    }
}
