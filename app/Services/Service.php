<?php
namespace App\Services;
use App\Models\Menu;
use Datatables;
use DB;


class Service implements ServiceContract {
    protected $model;
    protected $name;

    public function datatables($param = array()) {
        return Datatables::queryBuilder(DB::table($this->name))
            ->addColumn('action','actions.'.$this->name)
            ->make(true);
    }

    public function find($id) {
        return $this->model->find($id);
    }

    public function delete($id) {
        return $this->model->find($id)->delete();
    }

    public function store($data) {
        return $this->model->create($data);
    }

    public function update($data, $id) {
        $model = $this->model->find($id);
        $model->fill($data);
        return $model->save();
    }

    public function filter($data, $limit = 10) {
        return $this->model->where($data)->take($limit)->get();
    }

    public function meta() {
        $path = request()->segment(1);
        if (\Cache::has('meta_'.$path)) {
            return \Cache::get('meta_'.$path);
        }

        $menus = Menu::where('path',$path)
            ->first(['name','description','path'])->toArray();
        \Cache::put('meta_'.$path,$menus);
        return $menus;
    }

    public function all() {
        return $this->model->get();
    }
}