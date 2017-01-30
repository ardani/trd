<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Services\CustomerTypeService;
use App\Services\ProductPriceService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductPricesController extends Controller
{
    private $page = 'product_prices';
    private $service;
    private $type;
    private $product;

    public function __construct(ProductPriceService $service, CustomerTypeService $type, ProductService $product) {
        $this->service = $service;
        $this->type = $type;
        $this->product = $product;
    }

    public function index($product_id) {
        if (request()->ajax()) {
            return $this->service->datatables(['product_id' => $product_id]);
        }
        $data = $this->service->meta();
        $data['product'] = $this->product->find($product_id);
        return view('pages.'.$this->page.'.index',$data);
    }

    public function show($id) {
        return view('pages.'.$this->page.'.show',$this->service->find($id));
    }

    public function create($product_id) {
        $data = $this->service->meta();
        $data['types'] = $this->type->all();
        $data['product'] = $this->product->find($product_id);
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(ProductsRequest $request) {
        $data = $request->all();
        $this->service->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($product_id,$id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        $data['types'] = $this->type->all();
        $data['product'] = $this->product->find($product_id);
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(ProductsRequest $request, $id) {
        $data = $request->all();
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($product_id,$id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }
}
