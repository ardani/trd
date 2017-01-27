<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Setting;
use Entrust;

class SettingService extends Service {

    protected $model;
    protected $name = 'settings';

    public function __construct(Setting $model) {
        $this->model = $model;
    }
}