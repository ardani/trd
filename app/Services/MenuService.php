<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Menu;
use Cache;
use Auth;
use Entrust;

class MenuService extends Service {

    protected $model;
    protected $name = 'menus';

    public function __construct(Menu $model) {
        $this->model = $model;
    }

    public function buildSideMenu() {
        if (Cache::has('menu_role_' . Auth::user()->role_id)) {
            return Cache::get('menu_role_' . Auth::user()->role_id);
        }

        $side_menus = [];
        $menus      = $this->model->where('parent', 0)->orderBy('order')->get();
        foreach ($menus as $menu) {
            foreach ($menu->childs as $child) {
                if (Entrust::can('view.' . $child->path)) {
                    $side_menus[ $menu->path ]['parent']   = $menu;
                    $side_menus[ $menu->path ]['groups'][] = $child->path;
                    $side_menus[ $menu->path ]['childs'][] = $child;
                }
            }
        }

        Cache::put('menu_role_' . Auth::user()->role_id, $side_menus, 60 * 3);

        return $side_menus;
    }

    public function menuRoute() {
        if (Cache::has('menu_route')) {
            return Cache::get('menu_route');
        }
        $menus = Menu::where('parent', '!=', 0)->orderBy('order')->get();
        Cache::put('menu_route', $menus, 60 * 3);

        return $menus;
    }

    public function all() {
        return $this->model->where('parent', 0)->orderBy('order')->get();
    }
}