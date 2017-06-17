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

class CashFlowService extends Service {

    protected $model;
    protected $name = 'cash_flows';

    public function __construct(CashFlow $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
       return false;
    }

    public function getData($account, $date) {
        $result = $this->model->where(function($query) use ($account, $date) {
                if ($date_untils = date_until($date)) {
                    $query->where('created_at','>=',$date_untils[0])
                        ->where('created_at','<=',$date_untils[1]);
                }

                if ($account) {
                    $query->where('account_code_id', $account);
                }
            })
            ->selectRaw('sum(debit) as sdebit, sum(credit) as scredit, sum(debit-credit) as saldo, account_code_id')
            ->groupBy('account_code_id')
            ->havingRaw('saldo != 0')
            ->orderBy('account_code_id')
            ->get();
        return $result;

    }
}