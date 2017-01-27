<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Category;
use Entrust;

class CategoryService extends Service {

    protected $model;
    protected $name = 'categories';

    public function __construct(Category $model) {
        $this->model = $model;
    }
}