<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\CorrectionStock;
use App\Models\TakeProduct;
use Entrust;
use Datatables;

class TakeProductService extends Service {

    protected $model;
    protected $name = 'take_products';

    public function __construct(TakeProduct $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        $model = $this->model->query();
        if ($search = request()->input('search')['value']) {
            $model->whereHas('product', function($query) use ($search) {
                $query->where('code','like','%'.$search.'%')
                    ->orWhere('name','like','%'.$search.'%');
            });
        }

        return Datatables::eloquent($model)
            ->addColumn('product_name', function($model){
                return $model->product->name;
            })
            ->addColumn('product_code', function($model){
                return $model->product->code;
            })
            ->editColumn('created_at', function($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}