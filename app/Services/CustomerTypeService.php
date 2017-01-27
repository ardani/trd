<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\CustomerType;
use Entrust;

class CustomerTypeService extends Service {

    protected $model;
    protected $name = 'customer_types';

    public function __construct(CustomerType $model) {
        $this->model = $model;
    }
}