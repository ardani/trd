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
        $sale = $this->service->find($no);
        $tmpdir = sys_get_temp_dir();
        $file =  tempnam($tmpdir, 'ctk');
        $connector = new FilePrintConnector($file);
        $printer = new Printer($connector);
        $printer->setFont(Printer::FONT_B);
        // header
        $address = explode('<br/>', setting('company.address'));
        $printer->text(str_pad(setting('company.name'), 95, ' ', STR_PAD_BOTH) . "\n");
        foreach ($address as $row) {
            $printer->text(str_pad($row, 95, ' ', STR_PAD_BOTH) . "\n");
        }
        $printer->text(str_repeat("\n", 1));
        // customer
        $cust_name = strtoupper($sale->customer->name);
        $printer->text("INVOICE TO $cust_name \n");
        $printer->text(str_pad('Address', 10).' : '.$sale->customer->address . "\n");
        $printer->text(str_pad('Phone', 10).' : '.$sale->customer->phone . "\n");
        $printer->text(str_repeat("\n", 1));
        // order
        $printer->text(str_pad('PO NUMBER', 10).' : '.$sale->no . "\n");
        $printer->text(str_pad('CREATE AT', 10).' : '.$sale->created_at->format('d M Y') . "\n");
        $payment_at = $sale->paid_until_at ? $sale->paid_until_at->format('d M Y') : '-';
        $printer->text(str_pad('PAYMENT', 10).' : '.$sale->payment_method->name . ' / ' .$payment_at . "\n");
        $printer->text(str_repeat("\n", 1));
        // column
        $printer->text(str_pad('No', 5));
        $printer->text(str_pad('Product', 40));
        $printer->text(str_pad('Qty', 20));
        $printer->text(str_pad('Price', 10, ' ', STR_PAD_LEFT));
        $printer->text(str_pad('Disc', 10, ' ', STR_PAD_LEFT));
        $printer->text(str_pad('Subtotal', 10, ' ', STR_PAD_LEFT). "\n");
        $printer->text(str_repeat('-', 95). "\n");
        $no = 1;
        foreach ($sale->transactions as $transaction) {
            $name_product = $transaction->product->name . ' ' . $transaction->desc;
            $wrap_product_name = wordwrap($name_product, 38, "\n", true);
            $product_name_lines = explode("\n", $wrap_product_name);
            $product_name_print = count($product_name_lines) ? $product_name_lines[0] : $name_product;

            $subtotal = number_format(abs($transaction->qty) * ($transaction->selling_price - $transaction->disc) * $transaction->attribute);
            $printer->text(str_pad($no, 5));
            $printer->text(str_pad($product_name_print, 40));
            $printer->text(str_pad(abs($transaction->qty) .' '. $transaction->units, 20));
            $printer->text(str_pad(number_format($transaction->selling_price), 10, ' ', STR_PAD_LEFT));
            $printer->text(str_pad(number_format($transaction->disc), 10, ' ', STR_PAD_LEFT));
            $printer->text(str_pad($subtotal, 10, ' ', STR_PAD_LEFT). "\n");
            if (count($product_name_lines) > 1) {
                foreach ($product_name_lines as $key => $value) {
                    if ($key == 0) continue;
                    $printer->text(str_pad('', 5));
                    $printer->text(str_pad($product_name_lines[$key], 40). "\n");
                }
            }
            $no++;
        }
        $printer->text(str_repeat('-', 95). "\n");
        $printer->text(str_pad('Disc', 8) . ' : ' . number_format($sale->disc) . "\n");
        $printer->text(str_pad('Total', 8) . ' : ' . number_format($sale->total - $sale->disc) . "\n");
        $printer->text(str_pad('Pay', 8) . ' : ' . number_format($sale->cash) . "\n");
        $remain = $sale->payment_method_id == 2 ? abs($sale->total - $sale->disc - abs($sale->payment->total)) : $sale->total - $sale->disc - $sale->cash;
        $printer->text(str_pad('Remain', 8) . ' : ' . number_format($remain) . "\n");
        $printer->text(str_pad('Note', 8) . ' : ' . wordwrap($sale->note, 90, "\n", true) ?: '-');
        $printer->text(str_repeat("\n", 2));
//        // Sign
        $printer->text(str_pad('Dikirim', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('Diterima', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('Diperiksa', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_repeat("\n", 4));
        $printer->text(str_pad('_______________', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('_______________', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('_______________', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_repeat("\n", 2));
//        /* Footer */
        $printer->text("Print at ".date('d-m-Y')." by ".auth()->user()->username);
        $printer->text("Created By ".$sale->employee->name);
        $printer->feed();
        $printer->close();
        $content = file_get_contents($file);
        return view('pages.sale_orders.print-invoice', ['content' => $content]);
    }

    public function printDo($no) {
        $sale = $this->service->find($no);
        $tmpdir = sys_get_temp_dir();
        $file =  tempnam($tmpdir, 'ctk');
        $connector = new FilePrintConnector($file);
        $printer = new Printer($connector);
        $printer->setFont(Printer::FONT_B);
        // header
        $address = explode('<br/>', setting('company.address'));
        $printer->text(str_pad(setting('company.name'), 95, ' ', STR_PAD_BOTH) . "\n");
        foreach ($address as $row) {
            $printer->text(str_pad($row, 95, ' ', STR_PAD_BOTH) . "\n");
        }
        $printer->text(str_repeat("\n", 1));
        // customer
        $cust_name = strtoupper($sale->customer->name);
        $printer->text("DELIVERY ORDER TO $cust_name \n");
        $printer->text(str_pad('Address', 10).' : '.$sale->customer->address . "\n");
        $printer->text(str_pad('Phone', 10).' : '.$sale->customer->phone . "\n");
        $printer->text(str_repeat("\n", 1));
        // order
        $printer->text(str_pad('PO NUMBER', 10).' : '.$sale->no . "\n");
        $printer->text(str_pad('CREATE AT', 10).' : '.$sale->created_at->format('d M Y') . "\n");
        $printer->text(str_repeat("\n", 1));
        // column
        $printer->text(str_pad('No', 5));
        $printer->text(str_pad('Product', 50));
        $printer->text(str_pad('Qty', 40). "\n");
        $printer->text(str_repeat('-', 95). "\n");
        $no = 1;
        foreach ($sale->transactions as $transaction) {
            $name_product = $transaction->product->name . ' ' . $transaction->desc;
            $wrap_product_name = wordwrap($name_product, 48, "\n", true);
            $product_name_lines = explode("\n", $wrap_product_name);
            $product_name_print = count($product_name_lines) ? $product_name_lines[0] : $name_product;
            $printer->text(str_pad($no, 5));
            $printer->text(str_pad($product_name_print, 50));
            $printer->text(str_pad(abs($transaction->qty) .' '. $transaction->units, 40) . "\n");
            if (count($product_name_lines) > 1) {
                foreach ($product_name_lines as $key => $value) {
                    if ($key == 0) continue;
                    $printer->text(str_pad('', 5));
                    $printer->text(str_pad($product_name_lines[$key], 50). "\n");
                }
            }
            $no++;
        }
        $printer->text(str_repeat('-', 95). "\n");
        $printer->text(str_pad('Note', 8) . ' : ' . wordwrap($sale->note, 90, "\n", true) ?: '-');
        $printer->text(str_repeat("\n", 2));
//        // Sign
        $printer->text(str_pad('Dikirim', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('Diterima', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('Diperiksa', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_repeat("\n", 4));
        $printer->text(str_pad('_______________', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('_______________', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_pad('_______________', 30, ' ', STR_PAD_BOTH));
        $printer->text(str_repeat("\n", 2));
//        /* Footer */
        $printer->text("Print at ".date('d-m-Y')." by ".auth()->user()->username);
        $printer->text("Created By ".$sale->employee->name);
        $printer->feed();
        $printer->close();
        $content = file_get_contents($file);
        return view('pages.sale_orders.print-invoice', ['content' => $content]);
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