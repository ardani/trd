<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Services\CategoryService;
use App\Services\ComponentUnitService;
use App\Services\ProductService;
use App\Services\SupplierService;
use App\Services\UnitService;
use Illuminate\Http\Request;
class ProductsController extends Controller
{
    private $page = 'products';
    private $service;
    private $category;
    private $supplier;
    private $unit;
    private $componentUnit;

    public function __construct(
        ProductService $service,
        CategoryService $category,
        SupplierService $supplier,
        UnitService $unit,
        ComponentUnitService $componentUnit
        ) {
        $this->service = $service;
        $this->category = $category;
        $this->supplier = $supplier;
        $this->unit = $unit;
        $this->componentUnit = $componentUnit;
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
        $data['suppliers'] = $this->supplier->all();
        $data['categories'] = $this->category->all();
        $data['units'] = $this->unit->all();
        $data['components'] = [];
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(ProductsRequest $request) {
        $data = $request->all();
        $this->service->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        $data['suppliers'] = $this->supplier->all();
        $data['categories'] = $this->category->all();
        $data['units'] = $this->unit->all();
        $data['components'] = $model->product_unit;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(ProductsRequest $request, $id) {
        $data = $request->all();
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }

    public function loadUnit($id) {
        try {
            $unit = $this->unit->find($id);
            return view('pages.products.component_unit', ['components' => $unit->component_unit]);
        } catch (\Exception $e) {
            return '';
        }
    }

    public function load() {
        $q = request()->input('q');
        if ($q) {
            $where =  function($query) use ($q){
                $query->whereRaw('can_sale=1 AND (name like "%'.$q.'%" OR code like "%'.$q.'%")');
            };
            $product = $this->service->filter($where,20);
            return $product->map(function($val,$key) {
                return [
                    'value' => $val->id,
                    'text' => $val->code.' - '.$val->name
                ];
            })->toArray();
        }
        return [];
    }

    public function loadRaw() {
        $q = request()->input('q');
        if ($q) {
            $where =  function($query) use ($q){
                $query->whereRaw('can_sale=0 AND (name like "%'.$q.'%" OR code like "%'.$q.'%")');
            };
            $product = $this->service->filter($where,20);
            return $product->map(function($val,$key) {
                return [
                    'value' => $val->id,
                    'text' => $val->code.' - '.$val->name
                ];
            })->toArray();
        }
        return [];
    }
}
