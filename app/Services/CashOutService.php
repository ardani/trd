<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;


use App\Models\CashFlow;
use Entrust;
use Datatables;

class CashOutService extends Service {

    protected $model;
    protected $name = 'cash_outs';

    public function __construct(CashFlow $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->where('account_code_id','>=',5000)
            ->addColumn('account_name',function($model){
                return $model->account_code->name;
            })
            ->addColumn('credit', function ($model){
                return $model->value > 0 ? 0 : $model->value;
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}