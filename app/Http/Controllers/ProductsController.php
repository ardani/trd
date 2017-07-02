<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Services\CategoryService;
use App\Services\ComponentUnitService;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SupplierService;
use App\Services\UnitService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private $page = 'products';
    private $service;
    private $category;
    private $supplier;
    private $unit;
    private $componentUnit;
    private $customer;

    public function __construct(
        ProductService $service,
        CategoryService $category,
        SupplierService $supplier,
        UnitService $unit,
        ComponentUnitService $componentUnit,
        CustomerService $customer
    )
    {
        $this->service = $service;
        $this->category = $category;
        $this->supplier = $supplier;
        $this->unit = $unit;
        $this->componentUnit = $componentUnit;
        $this->customer = $customer;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->service->datatables();
        }

        return view('pages.' . $this->page . '.index', $this->service->meta());
    }

    public function show($id)
    {
        return view('pages.' . $this->page . '.show', $this->service->find($id));
    }

    public function create()
    {
        $data = $this->service->meta();
        $data['suppliers'] = $this->supplier->all();
        $data['categories'] = $this->category->all();
        $data['units'] = $this->unit->all();
        $data['product_units'] = [];
        return view('pages.' . $this->page . '.create', $data);
    }

    public function store(ProductsRequest $request)
    {
        $data = $request->all();
        $data['stock_at'] = empty($data['stock_at']) ? null : Carbon::createFromFormat('d/m/Y', $data['stock_at'])->format('Y-m-d');
        $data['code'] = auto_number_product($data['name']);
        $this->service->store($data);
        return redirect()->back()->with('message', 'Save Success');
    }

    public function edit($id)
    {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        $data['suppliers'] = $this->supplier->all();
        $data['categories'] = $this->category->all();
        $data['units'] = $this->unit->all();
        $data['product_units'] = $this->mappingUnit($model->product_unit);
        $data['stock_at'] = empty($model['stock_at']) ? null : $model['stock_at']->format('d/m/Y');
        return view('pages.' . $this->page . '.edit', $data);
    }

    private function mappingUnit($product_units)
    {
        $newUnits = [];
        foreach ($product_units as $unit) {
            $newUnits[$unit->unit_id][$unit->component_unit_code] = $unit->value;
        }
        return $newUnits;
    }

    public function update(ProductsRequest $request, $id)
    {
        $data = $request->all();
        $data['stock_at'] = empty($data['stock_at']) ? null : Carbon::createFromFormat('d/m/Y', $data['stock_at'])->format('Y-m-d');
        $this->service->update($data, $id);
        return redirect()->back()->with('message', 'Update Success');
    }

    public function delete($id)
    {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }

    public function loadUnit($id)
    {
        try {
            $unit = $this->unit->find($id);
            return view('pages.products.component_unit', ['components' => $unit->component_unit]);
        } catch (\Exception $e) {
            return '';
        }
    }

    public function load()
    {
        $q = request()->input('q');
        $customer_type_id = $this->customer->find(request()->input('customer_id',1))->customer_type_id;
        if ($q) {
            $where = function ($query) use ($q) {
                $query->whereRaw('can_sale=1 AND (name like "%' . $q . '%" OR code like "%' . $q . '%")');
            };
            $product = $this->service->filter($where, 20);
            return $product->map(function ($val) use ($customer_type_id) {
                $selling_price_customer = $val->product_price()->where('customer_type_id', $customer_type_id)->first();
                $selling_price  = $selling_price_customer ? $selling_price_customer->selling_price : $val->selling_price_default;

                $units['data'] = [
                    'sellingprice' => $selling_price
                ];

                foreach ($val->product_unit as $unit) {
                    $units['data'][$unit->component_unit_code] = $unit->value;
                }

                return array_merge([
                    'value' => $val->id,
                    'text' => $val->code . ' - ' . $val->name
                ], $units);

            })->toArray();
        }
        return [];
    }

    public function loadRaw()
    {
        $q = request()->input('q');
        if (!$q) {
            return [];
        }
        $where = function ($query) use ($q) {
            $query->whereRaw('(name like "%' . $q . '%" OR code like "%' . $q . '%")')
                ->whereIn('category_id', [1, 3]);
        };

        $product = $this->service->filter($where, 20);
        return $product->map(function ($val) {
            $appends['data'] = [
                'sellingprice' => $val->selling_price_default
            ];

            foreach ($val->product_unit as $unit) {
                $appends['data'][$unit->component_unit_code] = $unit->value;
            }

            return array_merge([
                'value' => $val->id,
                'text' => $val->code . ' - ' . $val->name
            ], $appends);

        })->toArray();
    }

    public function loadProduction()
    {
        $q = request()->input('q');
        if (!$q) {
            return [];
        }
        $where = function ($query) use ($q) {
            $query->whereRaw('(name like "%' . $q . '%" OR code like "%' . $q . '%")')
                ->where('category_id','!=', 2);
        };

        $product = $this->service->filter($where, 20);
        return $product->map(function ($val) {
            $appends['data'] = [
                'sellingprice' => $val->selling_price_default
            ];

            foreach ($val->product_unit as $unit) {
                $appends['data'][$unit->component_unit_code] = $unit->value;
            }

            return array_merge([
                'value' => $val->id,
                'text' => $val->code . ' - ' . $val->name
            ], $appends);

        })->toArray();
    }
}
