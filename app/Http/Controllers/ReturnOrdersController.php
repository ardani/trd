<?php
namespace App\Http\Controllers;
use App\Services\ReturnOrderService;
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

    public function __construct(ReturnOrderService $service) {
        $this->service  = $service;
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
        $auto_number               = auto_number_sales();
        $data['auto_number_sales'] = $auto_number;
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
        $total          = $model->transactions->sum(function ($val) {
            return abs($val->qty) * ($val->selling_price - $val->disc);
        });
        $data['id']     = $id;
        $data['model']  = $model;
        $data['total']  = $total;
        $data['charge'] = $model->cash - $total;

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
}