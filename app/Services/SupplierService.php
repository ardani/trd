<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Supplier;
use Entrust;
use Datatables;

class SupplierService extends Service {

    protected $model;
    protected $name = 'suppliers';

    public function __construct(Supplier $model) {
        $this->model = $model;
    }
}