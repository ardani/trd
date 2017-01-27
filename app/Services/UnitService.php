<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Unit;
use Entrust;

class UnitService extends Service {

    protected $model;
    protected $name = 'units';

    public function __construct(Unit $model) {
        $this->model = $model;
    }
}