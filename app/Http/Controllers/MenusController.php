<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenusRequest;
use App\Services\MenuService;

class MenusController extends Controller
{
    private $page = 'menus';
    private $service;

    public function __construct(MenuService $service) {
        $this->service = $service;
    }

    public function index() {
        if (request()->ajax()) {
            return $this->service->datatables();
        }

        return view('pages.'.$this->page.'.index',$this->service->meta());
    }

    public function show($id) {
        return view('pages.'.$this->page.'.show',$this->service->find($id));
    }

    public function create() {
        $data = $this->service->meta();
        $data['parents'] = $this->service->all();
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(MenusRequest $request) {
        $data = $request->all();
        $this->service->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        $data['parents'] = $this->service->all();
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(MenusRequest $request,$id) {
        $data = $request->all();
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }
}
