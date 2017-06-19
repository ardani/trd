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
use Entrust;

class AccountCodeService extends Service {

    protected $model;
    protected $name = 'account_codes';
    private $cash;

    public function __construct(AccountCode $model, CashFlow $cash) {
        $this->model = $model;
        $this->cash = $cash;
    }

    public function all() {
        $key = $this->name;
        if (cache($key)) {
            return cache($key);
        }
        return cache([$key => $this->model->get()], 60);
    }

    public function getCash() {
        return $this->model->where('type', 1)->where('parent', 0)->get();
    }

    public function getData() {
        $result = $this->cash
            ->selectRaw('sum(debit) as debit, sum(credit) as credit, sum(debit-credit) as saldo, account_code_id')
            ->groupBy('account_code_id')
            ->orderBy('id')
            ->get()
            ->keyBy('account_code_id')
            ->toArray();
        return $result;
    }
}