<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use App\Services\TakeProductService;
use Illuminate\Http\Request;
class TakeProductsController extends Controller
{
    private $page = 'take_products';
    private $service;

    public function __construct(TakeProductService $service) {
        $this->service = $service;
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
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $units = [];
        $attribute = 1;
        $data['cashier_id'] = auth()->user()->id;
        foreach ($request->attribute as $key => $attribute) {
            $units[] = $attribute.$request->units[$key];
            $attribute *= $attribute;
        }

        $data['attribute'] = $attribute;
        $data['units'] = implode('x', $units);

        $this->service->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $data['cashier_id'] = auth()->user()->id;
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }
}
