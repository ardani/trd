<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Product;
use Entrust;
use Datatables;

class ProductService extends Service {

    protected $model;
    protected $name = 'products';

    public function __construct(Product $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('selling_price',function ($model){
                return view('actions.selling_price',['model' => $model]);
            })
            ->addColumn('category',function($model){
                return $model->category->name;
            })
            ->addColumn('supplier',function($model){
                return ($model->supplier_id) ? $model->supplier->name : '-';
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }

    public function store($data) {
        $product = $this->model->create($data);
        $product_units = collect($data['component'])->map(function($val,$key){
            return [
                'component_unit_code' => $key,
                'value' => $val
            ];
        })->toArray();
        foreach ($product_units as $product_unit) {
            $product->product_unit()->create($product_unit);
        }
        return true;
    }

    public function update($data, $id) {
        $product = $this->model->find($id);
        $product->fill($data)->save();
        $product->product_unit()->delete();
        $product_units = collect($data['component'])->map(function($val,$key){
            return [
              'component_unit_code' => $key,
              'value' => $val
            ];
        })->toArray();
        foreach ($product_units as $product_unit) {
            $product->product_unit()->create($product_unit);
        }

        return true;
    }
}