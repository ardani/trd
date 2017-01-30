<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\ProductPrice;
use Entrust;
use Datatables;

class ProductPriceService extends Service {

    protected $model;
    protected $name = 'product_prices';

    public function __construct(ProductPrice $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('customer_type',function($model){
                return $model->customer_type->name;
            })
            ->where('product_id',$param['product_id'])
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}