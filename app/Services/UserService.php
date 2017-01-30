<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Entrust;
use Datatables;
use DB;

class UserService extends Service {

    protected $model;
    protected $name = 'users';
    private $employee;

    public function __construct(User $model,Employee $employee) {
        $this->model = $model;
        $this->employee = $employee;
    }

    public function datatables($param = array()) {
        return Datatables::eloquent($this->model->query())
            ->addColumn('role',function($model) {
                $role = $model->roles->first();
                return $role->display_name;
            })
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }

    public function unregister() {
        $ids = $this->model->get(['id'])->toArray();
        return $this->employee->whereNotIn('id',$ids)->get();
    }
}