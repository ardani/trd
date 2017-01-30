<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
class UsersController extends Controller
{
    private $page = 'users';
    private $service;
    private $roleService;
    private $employeeService;

    public function __construct(UserService $service, RoleService $role, EmployeeService $employee) {
        $this->service = $service;
        $this->roleService = $role;
        $this->employeeService = $employee;
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
        $data['roles'] = $this->roleService->all();
        $data['employees'] = $this->service->unregister();
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request) {
        if($this->service->find($request->id)){
            return redirect()->back()->withErrors('User already exist','exist');
        }
        $data = $request->all();
        $this->service->store($data);
        $user = $this->service->find($request->id);
        $user->roles()->attach($request->role_id);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        $data['roles'] = $this->roleService->all();
        $data['employees'] = $this->employeeService->all();
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
}
