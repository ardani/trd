<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */
namespace App\Services;
use App\Models\Cash;
use App\Models\CashFlow;
use Carbon\Carbon;
use Entrust;
use Datatables;

class CashInService extends Service {

    protected $model;
    private $cash_flow;
    protected $name = 'cash_ins';

    public function __construct(Cash $model, CashFlow $cash_flow) {
        $this->model = $model;
        $this->cash_flow = $cash_flow;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->where('type', 1)
            ->editColumn('created_at', function ($model){
                return $model->created_at->format('d/m/Y');
            })
            ->addColumn('account_cash_name', function ($model){
                return $model->account_cash->name;
            })
            ->addColumn('total', function ($model){
                return number_format($model->total);
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

    public function store($data) {
        $no = auto_number_cash_in();
        $model = $this->model->firstOrNew(['no' => $no]);
        $model->no = $no;
        $created_at = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $model->created_at = $created_at;
        $model->type = 1;
        $model->account_cash_id = $data['account_cash_id'];
        $model->cashier_id = auth()->id();
        $model->save();

        $sessions = session($data['no']);
        $model->details()->delete();
        $total = 0;
        foreach ($sessions as $session) {
            $model->details()->create([
                'account_code_id' => $session['account_code_id'],
                'debit' => $session['debit'],
                'mutation' => $session['mutation'],
                'note' => $session['note'],
                'created_at' => $created_at
            ]);
            $total += $session['debit'];
        }

        $model->details()->create([
            'account_code_id' => $data['account_cash_id'],
            'debit' => $total,
            'note' => 'cash in from '.$data['no'],
            'created_at' => $created_at
        ]);

        return $model;
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $data['created_at'] = Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d');
        $this->cash_flow->where('cash_id', $id)
            ->where('account_code_id', $model->account_cash_id)
            ->delete();

        $model->fill($data);
        $model->save();
        $model->details()->create([
            'account_code_id' => $data['account_cash_id'],
            'debit' => $model->total,
            'note' => 'cash in from '.$data['no'],
            'created_at' => $data['created_at']
        ]);
        return $model;
    }

    public function getData($date) {
        $result = $this->model->where('type', 1)
            ->where(function ($query) use ($date) {
                if ($date_untils = date_until($date)) {
                    $query->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }
            })
            ->orderBy('id')
            ->get();
        return $result;
    }

    public function delete($id) {
        $data = $this->model->find($id);
        $this->cash_flow->where('cash_id', $id)->delete();
        return $data->delete();
    }
}