<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\ComponentUnit;
use Entrust;
use Datatables;
use DB;

class ComponentUnitService extends Service {

    protected $model;
    protected $name = 'component_units';

    public function __construct(ComponentUnit $model) {
        $this->model = $model;
    }

    public function datatables($param = array()) {
        return Datatables::queryBuilder(DB::table($this->name))
            ->where('unit_id',$param['unit_id'])
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}