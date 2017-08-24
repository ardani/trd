<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class OrdersController extends Controller
{
    private $page = 'orders';
    private $service;
    private $supplier;
    private $product;

    public function __construct(OrderService $service, SupplierService $supplier, ProductService $product) {
        $this->service = $service;
        $this->supplier = $supplier;
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

    public function create() {
        $data = $this->service->meta();
        $auto_number = auto_number_orders();
        $data['auto_number_sales'] = $auto_number;
        $data['transactions'] = session($auto_number);
        $total = 0;
        if (session($auto_number)) {
            $total = collect(session($auto_number))->sum(function($val){
                return $val['purchase_price'] * $val['qty'] * $val['attribute'];
            });
        }
        $data['total'] = $total;
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        return $this->service->store($data);
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $total = $model->transactions->sum(function ($val){
            return abs($val->qty) * ($val->purchase_price) * $val->attribute;
        });
        $data['id'] = $id;
        $data['model'] = $model;
        $data['total'] = $total;
        $data['charge'] = $model->cash - $total;
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

    // create PO
    public function viewTempPODetail($no) {
        return session($no);
    }

    public function addTempPODetail(Request $request) {
        $sessions = session()->has($request->no) ? session($request->no) : [];
        $product = $this->product->find($request->product_id);
        $purchase_price = $request->purchase_price;
        $selling_price = $request->selling_price;

        $id = md5(time());
        $sessions[ $id ] = [
            'id'             => $id,
            'product_id'     => $request->product_id,
            'code'           => $product->code,
            'name'           => $product->name,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'purchase_price' => $purchase_price,
            'selling_price'  => $selling_price,
            'subtotal'       => $request->qty * $purchase_price * $request->attribute
        ];

        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no.'.'.$request->id);
        return array_values($this->viewTempPODetail($request->no));
    }

    // edit PO
    public function viewPODetail($no) {
        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $transactions = $PO->transactions->map(function($val,$key){
            $qty = $val->qty;
            return  [
                'id'             => $val->id,
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'qty'            => $qty,
                'purchase_price' => $val->purchase_price,
                'selling_price'  => $val->selling_price,
                'attribute'      => $val->attribute,
                'units'          => $val->units,
                'subtotal'       => $qty * ($val->purchase_price) * $val->attribute
            ];
        });

        return $transactions->keyBy('id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no = $request->no;
        $transactions = $this->viewPODetail($no);
        $product = $this->product->find($request->product_id);
        $purchase_price = $product->purchase_price;
        $selling_price = $product->selling_price;

        $key = md5(time());
        $transactions[ $key ] = [
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name,
            'attribute'      => $request->attribute,
            'units'          => $request->units,
            'qty'            => $request->qty,
            'purchase_price' => $purchase_price,
            'selling_price'  => $selling_price,
            'subtotal'       => $request->qty * ($purchase_price) * $request->attribute
        ];

        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $PO->transactions()->create($transactions[$key]);
        return array_values($this->viewPODetail($no));
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function($query) use ($no) {
            $query->where('no',$no);
        });

        $PO->transactions()->where('product_id',$request->product_id)->delete();
        return array_values($this->viewPODetail($no));
    }

    public function printInvoice($no) {
        $order = $this->service->find($no);
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
        // supplier
        $supplier_name = strtoupper($order->supplier->name);
        $printer->text("INVOICE TO $supplier_name \n");
        $printer->text(str_pad('Address', 10).' : '.$order->supplier->address . "\n");
        $printer->text(str_pad('Phone', 10).' : '.$order->supplier->phone . "\n");
        $printer->text(str_repeat("\n", 1));
        // order
        $printer->text(str_pad('PO NO', 10).' : '.$order->no . "\n");
        $printer->text(str_pad('INVOICE NO', 10).' : '.$order->invoice_no . "\n");
        $printer->text(str_pad('DO NO', 10).' : '.$order->delivery_order_no . "\n");
        $printer->text(str_pad('CREATE AT', 10).' : '.$order->created_at->format('d M Y') . "\n");
        $payment_at = $order->paid_until_at ? $order->paid_until_at->format('d M Y') : '-';
        $printer->text(str_pad('PAYMENT', 10).' : '.$order->payment_method->name . ' / ' .$payment_at . "\n");
        $printer->text(str_repeat("\n", 1));
        // column
        $printer->text(str_pad('No', 5));
        $printer->text(str_pad('Product', 50));
        $printer->text(str_pad('Qty', 20));
        $printer->text(str_pad('Price', 10, ' ', STR_PAD_LEFT));
        $printer->text(str_pad('Subtotal', 10, ' ', STR_PAD_LEFT). "\n");
        $printer->text(str_repeat('-', 95). "\n");
        $no = 1;
        foreach ($order->transactions as $transaction) {
            $name_product = $transaction->product->name . ' ' . $transaction->desc;
            $wrap_product_name = wordwrap($name_product, 48, "\n", true);
            $product_name_lines = explode("\n", $wrap_product_name);
            $product_name_print = count($product_name_lines) ? $product_name_lines[0] : $name_product;

            $subtotal = number_format(abs($transaction->qty) * ($transaction->purchase_price) * $transaction->attribute);
            $printer->text(str_pad($no, 5));
            $printer->text(str_pad($product_name_print, 50));
            $printer->text(str_pad(abs($transaction->qty) .' '. $transaction->units, 20));
            $printer->text(str_pad(number_format($transaction->purchase_price), 10, ' ', STR_PAD_LEFT));
            $printer->text(str_pad($subtotal, 10, ' ', STR_PAD_LEFT). "\n");
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
        $printer->text(str_pad('Total', 8) . ' : ' . number_format($order->total) . "\n");
        $printer->text(str_pad('Pay', 8) . ' : ' . number_format($order->cash) . "\n");
        $remain = $order->payment_method_id == 2 ? abs($order->total - abs($order->payment->total)) : $order->total - $order->cash;
        $printer->text(str_pad('Remain', 8) . ' : ' . number_format($remain) . "\n");
        $printer->text(str_pad('Note', 8) . ' : ' . wordwrap($order->note, 90, "\n", true) ?: '-');
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
        $printer->text(" Created By ".$order->employee->name);
        $printer->feed();
        $printer->close();
        $content = file_get_contents($file);
        return view('pages.orders.print-invoice', ['content' => $content]);
    }

    public function load() {
        $q = request()->input('q');
        if (!$q) {
            return [];
        }
        $where =  function($query) use ($q){
            $query->whereRaw('(no like "%'.$q.'%")');
        };
        $order = $this->service->filter($where,20);
        return $order->map(function($val,$key) {
            return [
                'value' => $val->id,
                'text' => $val->no.' - '.$val->supplier->name,
                'data' => [
                    'supplier' => $val->supplier->name,
                ]
            ];
        })->toArray();
    }

    public function detail() {
        $q = request()->input('id');
        if (!$q) {
            return [];
        }

        $sale = $this->service->find($q);
        return $sale->transactions->map(function($val){
            return [
                'code' => $val->product->code,
                'name' => $val->product->name,
                'product_id' => $val->product_id,
                'qty' => abs($val->qty)
            ];
        })->toArray();
    }
}
