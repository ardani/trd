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
        $model = $this->model->whereHas('sale_order', function ($q) {
            if ($state_id = request()->input('state_id')) {
                $q->where('state_id', $state_id);
            }
        });

        return Datatables::eloquent($model)
            ->editColumn('created_at',function($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('note', function ($model){
                return $model->sale_order->note;
            })
            ->addColumn('state',function ($model) {
                return $model->sale_order->state->name;
            })
            ->addColumn('sale_order_code',function($model) {
                return $model->sale_order->no;
            })
            ->addColumn('action',function ($model) {
                $data = [
                    'id' => $model->id,
                    'state_id' => $model->sale_order->state_id
                ];
                return view('actions.'.$this->name, $data);
            })
            ->orderBy('id','Desc')
            ->where(function ($model) {
                if ($date_untils = date_until(request()->input('date_until'))) {
                    $model->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }
            })
            ->make(true);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $model->cashier_id = \Auth::id();
        $purchase = $this->sale->find($model->sale_order_id);
        $purchase->update(['state_id' => $data['state_id']]);
        return $model->save();
    }
}