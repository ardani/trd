<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SaleOrderService;
use App\Services\SaleService;
use App\Services\ShopService;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use PDF;

class SaleOrdersController extends Controller {
    private $page = 'sale_orders';
    private $service;
    private $customer;
    private $product;
    private $shop;

    public function __construct(
        SaleOrderService $service,
        CustomerService $customer,
        ProductService $product,
        ShopService $shop) {

        $this->service  = $service;
        $this->customer = $customer;
        $this->product  = $product;
        $this->shop = $shop;
    }

    public function index() {
        if (request()->ajax()) {
            return $this->service->datatables();
        }
        $data           = $this->service->meta();
        $data['shops']  = $this->shop->all();
        return view('pages.' . $this->page . '.index', $data);
    }

    public function show($id) {
        return view('pages.' . $this->page . '.show', $this->service->find($id));
    }

    public function create() {
        $data                      = $this->service->meta();
        $auto_number               = auto_number_sales();
        $data['auto_number_sales'] = $auto_number;
        $data['transactions']      = session($auto_number);
        $data['total']             = number_format(collect($data['transactions'])->sum('subtotal'));
        $data['shops']             = $this->shop->all();
        return view('pages.' . $this->page . '.create', $data);
    }

    public function store(Request $request) {
        $data = $request->all();
        return $this->service->store($data);
    }

    public function edit($id) {
        $model          = $this->service->find($id);
        $data           = $this->service->meta();
        $total          = $model->transactions->sum(function ($val) {
            return abs($val->qty) * ($val->selling_price - $val->disc) * $val->attribute;
        });

        $data['id']     = $id;
        $data['model']  = $model;
        $data['total']  = $total;
        $data['disc']   = $model->disc ?: 0;
        $data['charge'] = $model->cash - $total;
        $data['shops']  = $this->shop->all();
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

    public function viewTempPODetail($no) {
        return session($no);
    }

    public function addTempPODetail(Request $request) {
        $sessions       = session()->has($request->no) ? session($request->no) : [];
        $product        = $this->product->find($request->product_id);
        $selling_price  = $request->selling_price;
        $purchase_price = $product->purchase_price_default;
        $disc           = $product->product_discount ? $product->product_discount->amount : 0;
        $desc           = $request->desc ? ' - '. $request->desc : '';

        $id = md5(time());
        $sessions[ $id ] = [
            'id'             => $id,
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name.$desc,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'desc'           => $request->desc,
            'disc'           => $disc,
            'selling_price'  => $selling_price,
            'purchase_price' => $purchase_price,
            'subtotal'       => $request->qty * ($selling_price - $disc) * $request->attribute
        ];

        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no . '.' . $request->id);
        return array_values($this->viewTempPODetail($request->no));
    }

    public function viewPODetail($no) {
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });

        $transactions = $PO->transactions->map(function ($val, $key) {
            $disc = $val->disc ?: 0;
            $qty  = abs($val->qty);
            $desc = $val->desc ? ' - '. $val->desc : '';
            return [
                'id'             => $val->id,
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name.$desc,
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

        return $transactions->keyBy('id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no             = $request->no;
        $transactions   = $this->viewPODetail($no);
        $product        = $this->product->find($request->product_id);
        $selling_price  = $request->selling_price;
        $purchase_price = $product->purchase_price_default;
        $disc           = $product->product_discount ? $product->product_discount->amount : 0;
        $desc           = $request->desc ? ' - '. $request->desc : '';
        $key = md5(time());
        $transactions[ $key ] = [
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name.$desc,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'desc'           => $request->desc,
            'disc'           => $disc,
            'selling_price'  => $selling_price,
            'purchase_price' => $purchase_price,
            'subtotal'       => $request->qty * ($selling_price - $disc) * $request->attribute
        ];

        $PO           = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $param        = $transactions[ $key ];
        $param['qty'] = $param['qty'] * -1;
        $PO->transactions()->create($param);

        return array_values($this->viewPODetail($no));
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $PO->transactions()->where('id', $request->id)->delete();
        return array_values($this->viewPODetail($no));
    }

    public function printInvoice($no) {
        $data = [
            'sale' => $this->service->find($no)
        ];

        $tmpdir = sys_get_temp_dir();
        $file =  tempnam($tmpdir, 'ctk');
//        /* Do some printing */
        $connector = new FilePrintConnector($file);
        $printer = new Printer($connector);
        $printer->setFont(Printer::FONT_B);
        $printer->setTextSize(1, 1);
        $items = array(
            new item("Example item #1", "4.00"),
            new item("Another thing", "3.50"),
            new item("Something else", "1.00"),
            new item("A final item", "4.45"),
        );
        $subtotal = new item('Subtotal', '12.95');
        $tax = new item('A local tax', '1.30');
        $total = new item('Total', '14.25', true);
        /* Date is kept the same for testing */
        $date = "Monday 6th of April 2015 02:56:25 PM";
        /* Name of shop */
        $printer -> text("ExampleMart Ltd.\n");
        $printer -> text("Shop No. 42.\n");
        $printer -> feed();
        $printer -> text("SALES INVOICE\n");
        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text(new item('', '$'));
        foreach ($items as $item) {
            $printer -> text($item);
        }
        $printer -> text($subtotal);
        $printer -> feed();
        /* Tax and total */
        $printer -> text($tax);
        $printer -> text($total);
        /* Footer */
        $printer -> feed(2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Thank you for shopping at ExampleMart\n");
        $printer -> text("For trading hours, please visit example.com\n");
        $printer -> feed(2);
        $printer -> text($date . ".\n");

        $printer -> close();
        $content = file_get_contents($file);
        $data['content'] = $content;
        return view('pages.sale_orders.print-invoice', $data);
    }

    public function printDo($no) {
        $data = [
            'sale' => $this->service->find($no)
        ];

        return view('pages.sale_orders.print-do', $data);
    }

    public function load() {
        if ($q = request()->input('q')) {
            $where =  function($query) use ($q){
                $query->whereRaw('(no like "%'.$q.'%")');
            };
            $sale = $this->service->filter($where,20);
            return $sale->map(function($val,$key) {
                return [
                    'value' => $val->id,
                    'text' => $val->no.' - '.$val->customer->name,
                    'data' => [
                        'customer' => $val->customer->name,
                    ]
                ];
            })->toArray();
        }
        return [];
    }

    public function detail() {
        if ($q = request()->input('id')) {
            $sale = $this->service->find($q);
            return $sale->transactions->map(function($val){
              return [
                  'code' => $val->product->code,
                  'name' => $val->product->name.' - '.$val->desc,
                  'product_id' => $val->product_id,
                  'qty' => abs($val->qty)
              ];
            })->toArray();
        }
        return [];
    }
}

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
