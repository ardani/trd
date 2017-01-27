<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\User;
use Entrust;
use Datatables;
use DB;

class UserService extends Service {

    protected $model;
    protected $name = 'users';

    public function __construct(User $model) {
        $this->model = $model;
    }

    public function datatables() {
        return Datatables::eloquent($this->model->query())
            ->addColumn('role',function($model) {
                $role = $model->roles->first();
                return $role->display_name;
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }
}