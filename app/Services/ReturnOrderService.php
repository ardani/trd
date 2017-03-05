<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;
use App\Models\ReturnOrder;
use Entrust;
use Datatables;

class ReturnOrderService extends Service {

    protected $model;
    protected $name = 'return_sale_orders';

    public function __construct(ReturnOrder $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('order_no',function ($model) {
                return $model->order->no;
            })

            ->editColumn('created_at',function ($model) {
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('id','Desc')
            ->make(true);
    }

    public function store($data) {

    }
}