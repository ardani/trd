<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Production;
use App\Models\SaleOrder;
use Entrust;
use Datatables;

class ProductionService extends Service {

    protected $model;
    protected $name = 'productions';
    private $sale;

    public function __construct(Production $model, SaleOrder $sale) {
        $this->model = $model;
        $this->sale = $sale;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->editColumn('created_at',function($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('note', function ($model){
                return $model->sale_order->note;
            })
            ->addColumn('state',function ($model) {
                return $model->sale_order->sale_order_state->state->name;
            })
            ->addColumn('sale_order_code',function($model) {
                return $model->sale_order->no;
            })
            ->addColumn('action',function ($model) {
                $data = [
                    'id' => $model->id,
                    'state_id' => $model->sale_order->sale_order_state->state_id
                ];
                return view('actions.'.$this->name, $data);
            })
            ->make(true);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $model->cashier_id = \Auth::id();
        $purchase = $this->sale->find($model->sale_order_id);
        $purchase->sale_order_state()->firstOrCreate(['state_id' => $data['state_id']]);
        return $model->save();
    }
}