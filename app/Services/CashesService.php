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

class CashesService extends Service {

    protected $model;
    private $cash_flow;
    protected $name = 'cashes';

    public function __construct(Cash $model, CashFlow $cash_flow) {
        $this->model = $model;
        $this->cash_flow = $cash_flow;
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
            ->selectRaw('sum(debit) as debit, sum(credit) as credit, sum(debit-credit) as saldo, account_code_id')
            ->groupBy('account_code_id')
            ->havingRaw('saldo != 0')
            ->orderBy('account_code_id')
            ->get();

        return $result;
    }
}