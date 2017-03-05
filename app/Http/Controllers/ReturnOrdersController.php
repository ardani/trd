<?php
namespace App\Http\Controllers;
use App\Services\ProductService;
use App\Services\ReturnOrderService;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 2/26/17
 * Time: 8:25 PM
 */

class ReturnOrdersController extends Controller
{
    private $page = 'return_orders';
    private $service;
    private $product;

    public function __construct(ReturnOrderService $service,ProductService $product) {
        $this->service  = $service;
        $this->product = $product;
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

    public function create() {
        $data                      = $this->service->meta();
        $auto_number               = auto_number_return_orders();
        $data['transactions']      = session($auto_number);

        return view('pages.' . $this->page . '.create', $data);
    }

    public function store(Request $request) {
        $data = $request->all();

        return $this->service->store($data);
    }

    public function edit($id) {
        $model          = $this->service->find($id);
        $data           = $this->service->meta();
        $data['id']     = $id;
        $data['model']  = $model;

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

    public function viewTempDetail($no) {
        return session($no);
    }

    public function viewDetail($no) {
        $data = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });

        $transactions = $data->transactions->map(function ($val, $key) {
            $qty  = abs($val->qty);
            return [
                'product_id'     => $val->product->id,
                'code'           => $val->product->code,
                'name'           => $val->product->name,
                'qty'            => $qty
            ];
        });
        return $transactions->keyBy('product_id')->toArray();
    }

    public function addTempDetail(Request $request) {
        $sessions       = session()->has($request->no) ? session($request->no) : [];
        $product        = $this->product->find($request->product_id);
        $sessions[ $product->id ] = [
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name,
            'qty'            => $request->qty
        ];

        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function addDetail(Request $request) {
        $no             = $request->no;
        $transactions   = $this->viewDetail($no);
        $product        = $this->product->find($request->product_id);
        $transactions[ $product->id ] = [
            'product_id'     => $product->id,
            'code'           => $product->code,
            'name'           => $product->name,
            'qty'            => $request->qty
        ];

        $data         = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $param        = $transactions[ $product->id ];
        $param['qty'] = $param['qty'] * -1;
        $data->transactions()->updateOrCreate(['product_id' => $product->id], $param);

        return array_values($transactions);
    }

    public function deleteTempDetail(Request $request) {
        session()->forget($request->no . '.' . $request->product_id);
        return array_values($this->viewTempPODetail($request->no));
    }

    public function deleteDetail(Request $request) {
        $no = $request->no;
        $data = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $data->transactions()->where('product_id', $request->product_id)->delete();
        return array_values($this->viewDetail($no));
    }

    public function complete(Request $request) {
        $no = $request->no;
        $data = $this->service->where(function ($query) use ($no) {
            $query->where('no', $no);
        });
        $data->is_complete = 1;
        $data->arrive_at = date('Y-m-d H:i:s');
        $data->save();
        $data->transactions()->update(['return_complete', 1]);
        return array_values($this->viewDetail($no));
    }
}