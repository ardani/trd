<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Entrust;

class RoleService extends Service {

    protected $model;
    protected $name = 'roles';
    private $permission;

    public function __construct(Role $model, Permission $permission) {
        $this->model = $model;
        $this->permission = $permission;
    }

    public function hasPermission($id) {
        $permissions = $this->permission->all();
        $role = $this->model->find($id);
        $roleperms = $role->perms->toArray();
        $roleperms = collect($roleperms)->keyBy('name');
        $permissions = $permissions->map(function($val,$key) use ($roleperms) {
            $status = $roleperms->has($val->name);
            return [
                'id' => $val->id,
                'name' => $val->name,
                'display_name' => $val->display_name,
                'status' => $status
            ];
        });
        return $permissions;
    }

    public function attachPermission($data,$id) {
        $role = $this->model->find($id);
        return $role->perms()->sync($data);
    }

    public function all() {
        return $this->model->get();
    }
}