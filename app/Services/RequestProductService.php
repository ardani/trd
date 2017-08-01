<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\CashFlow;
use App\Models\Payment;
use App\Models\Production;
use App\Models\RequestProduct;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Entrust;
use Datatables;

class RequestProductService extends Service {

    protected $model;
    protected $name = 'request_products';

    public function __construct(RequestProduct $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {

        return Datatables::eloquent($this->model->query())
            ->addColumn('created_by',function ($model) {
                return $model->employee->name;
            })
            ->addColumn('state',function ($model) {
                return $model->state->name;
            })
            ->editColumn('created_at',function ($model) {
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->orderBy('id','Desc')
            ->where(function ($model) {
                if ($date_untils = date_until(request()->input('date_until'))) {
                    $model->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }

                if ($state_id = request()->input('state_id')) {
                    $model->where('state_id', $state_id);
                }
            })
            ->make(true);
    }

    public function store($data) {
        $no = auto_number_request_product();
        $model = $this->model->firstOrNew(['no' => $no]);
        $model->no = $no;
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $model->created_at = $created_at;
        $model->note = $data['note'];
        $model->cashier_id = auth()->id();
        $model->save();
        $sessions = session($data['no']);
        $model->transactions()->delete();
        foreach ($sessions as $session) {
            $model->transactions()->create([
                'attribute' => $session['attribute'],
                'units' => $session['units'],
                'product_id' => $session['product_id'],
                'qty' => $session['qty']
            ]);
        }
        return clear_nota($data['no']);
    }

    public function delete($id)
    {
        $note = request()->input('note','');
        $this->model->find($id)->update(['note' => $note]);
        return parent::delete($id);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $model->fill($data);
        return $model->save();
    }
}