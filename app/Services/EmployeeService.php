<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Employee;
use Entrust;
use Datatables;

class EmployeeService extends Service {

    protected $model;
    protected $name = 'employees';

    public function __construct(Employee $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('type',function($model){
                return $model->employee_type->name;
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}