<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;
use App\Models\ReturnSaleOrder;
use Carbon\Carbon;
use Entrust;
use Datatables;

class ReturnSaleOrderService extends Service {

    protected $model;
    protected $name = 'return_sale_orders';

    public function __construct(ReturnSaleOrder $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('sale_order_no',function ($model) {
                return $model->sale_order->no;
            })
            ->editColumn('created_at',function ($model) {
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('id','Desc')
            ->make(true);
    }

    public function store($data) {
        $no = auto_number_return_sales();
        $model = $this->model->firstOrNew(['no' => $no]);
        $model->no = $no;
        $model->sale_order_id = $data['no_sale'];
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $model->note = $data['note'];
        $model->created_at = $created_at;
        $model->cashier_id = auth()->id();
        $model->save();
        $sessions = session($data['no']);
        $model->transactions()->delete();
        foreach ($sessions as $session) {
            $model->transactions()->create([
                'product_id' => $session['product_id'],
                'qty' => $session['qty']
            ]);
        }
        return clear_nota($data['no']);
    }
}