<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\ProductDiscount;
use Entrust;
use Datatables;

class DiscountService extends Service {

    protected $model;
    protected $name = 'discounts';

    public function __construct(ProductDiscount $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        $search = request()->input('search');
        $model = $this->model->whereHas('product',function($query) use ($search) {
            $query->where('name','like','%'.$search['value'].'%');
        });
        return Datatables::eloquent($model)
            ->addColumn('product',function($model){
                return $model->product->name;
            })
            ->editColumn('expired_at',function($model){
                return $model->expired_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}