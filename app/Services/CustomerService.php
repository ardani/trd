<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Customer;
use Entrust;
use Datatables;

class CustomerService extends Service {

    protected $model;
    protected $name = 'customers';

    public function __construct(Customer $model) {
        $this->model = $model;
    }

    public function datatables() {
        return Datatables::eloquent($this->model->query())
            ->addColumn('type',function($model){
                return $model->customer_type->name;
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}