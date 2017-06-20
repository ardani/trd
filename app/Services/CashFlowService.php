<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;


use App\Models\AccountCode;
use App\Models\CashFlow;
use Carbon\Carbon;
use Entrust;
use Datatables;

class CashFlowService extends Service {

    protected $model;
    protected $name = 'cash_flows';
    private $account;

    public function __construct(CashFlow $model, AccountCode $accountCode) {
        $this->model = $model;
        $this->account = $accountCode;
    }

    public function datatables($param = array()) {
       return false;
    }

    private function listAccount() {
        return $this->account->where(['type' => 1,'parent' => 0])
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function getData($account, $date) {
        $date_untils = date_until($date);
        $result = $this->model->where(function($query) use ($account, $date_untils) {
                if ($date_untils) {
                    $query->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }

                if ($account) {
                    $query->where('account_code_id', $account);
                }
            })
            ->whereIn('account_code_id', $this->listAccount())
            ->orderBy('id')
            ->get();
        return ['present' => $result, 'last' => $this->getDataLast($date)];
    }

    private function getDataLast($date) {
        $dates = explode(' - ', $date);
        $dates = Carbon::createFromFormat('d/m/Y', $dates[0]);

        $results = $this->model->where('created_at', '<', $dates)
            ->selectRaw('sum(debit) as debit, sum(credit) as credit, sum(debit-credit) as saldo, account_code_id')
            ->groupBy('account_code_id')
            ->whereIn('account_code_id', $this->listAccount())
            ->orderBy('account_code_id')
            ->get();

        $lastMonth = [
            'created_at' => $dates->format('d/M/Y'),
            'debit' => 0,
            'credit' => 0,
            'saldo' => 0
        ];
        foreach ($results as $result) {
            $lastMonth['debit'] += $result->debit;
            $lastMonth['credit'] += $result->credit;
            $lastMonth['saldo'] += $result->saldo;
        }
        return $lastMonth;
    }

}