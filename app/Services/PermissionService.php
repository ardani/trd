<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Permission;

class PermissionService extends Service {

    protected $model;
    protected $name = 'permissions';

    public function __construct(Permission $model) {
        $this->model = $model;
    }
}