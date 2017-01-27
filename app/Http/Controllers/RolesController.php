<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Http\Request;
class RolesController extends Controller
{
    private $page = 'roles';
    private $service;

    public function __construct(RoleService $service) {
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
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        $data = $request->all();
        $this->service->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }

    public function hasPermission($id) {
        $data = $this->service->meta();
        $data['role'] = $this->service->find($id);
        $data['permissions'] = $this->service->hasPermission($id);
        return view('pages.roles.permission', $data);
    }

    public function attachPermission(Request $request, $id) {
        $data = $request->input('permissions');
        $this->service->attachPermission($data,$id);
        return redirect()->back()->with('message','Update Success');
    }
}
