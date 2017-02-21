<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Production;
use App\Models\PurchaseOrder;
use Entrust;
use Datatables;

class ProductionService extends Service {

    protected $model;
    protected $name = 'productions';
    private $purchase;

    public function __construct(Production $model, PurchaseOrder $purchase) {
        $this->model = $model;
        $this->purchase = $purchase;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->editColumn('created_at',function($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('state',function ($model) {
                return $model->purchase_order->purchase_order_state->state->name;
            })
            ->addColumn('purchase_order_code',function($model){
                return $model->purchase_order->no;
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $model->cashier_id = \Auth::id();
        $model->note = $data['note'];
        $purchase = $this->purchase->find($model->purchase_order_id);
        $purchase->purchase_order_state()->firstOrCreate(['state_id' => $data['state_id']]);
        $model->save();
        return $model->save();
    }
}