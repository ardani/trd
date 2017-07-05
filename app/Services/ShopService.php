<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Customer;
use App\Models\Shop;
use Entrust;
use Datatables;

class ShopService extends Service {

    protected $model;
    protected $name = 'shops';

    public function __construct(Shop $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}