<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */
namespace App\Services;
use App\Models\CashFlow;
use Carbon\Carbon;
use Entrust;
use Datatables;

class CashInService extends Service {

    protected $model;
    protected $name = 'cash_ins';

    public function __construct(CashFlow $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->where('value','>',0)
            ->addColumn('account_name',function($model){
                return $model->account_code->name;
            })
            ->addColumn('pay_to',function($model){
                if ($model->account_code_ref_id) {
                    return $model->account_code_ref_id .' - '.$model->account_code_ref->name;
                }
                return '-';
            })
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('action','actions.'.$this->name)
            ->where(function ($model) {
                if ($date_untils = date_until(request()->input('date_until'))) {
                    $model->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }
            })
            ->orderBy('id', 'Desc')
            ->make(true);
    }

    public function getData($date = '') {
        $result = $this->model->where(function ($query) use ($date) {
            if ($date) {
                $dates = explode(' - ', $date);
                $start_at = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');
                $finish_at = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d');
                $query->whereBetween('created_at', [$start_at, $finish_at]);
            }
            $query->where('value','>',0);
        })->get();

        return $result;
    }
}