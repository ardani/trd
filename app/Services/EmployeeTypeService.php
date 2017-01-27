<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\EmployeeType;
use Entrust;

class EmployeeTypeService extends Service {

    protected $model;
    protected $name = 'employee_types';

    public function __construct(EmployeeType $model) {
        $this->model = $model;
    }
}