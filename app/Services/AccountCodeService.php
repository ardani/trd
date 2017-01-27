<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\AccountCode;
use Entrust;

class AccountCodeService extends Service {

    protected $model;
    protected $name = 'account_codes';

    public function __construct(AccountCode $model) {
        $this->model = $model;
    }
}