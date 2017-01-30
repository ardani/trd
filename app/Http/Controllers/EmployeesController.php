<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\CustomerTypeService;
use App\Services\EmployeeService;
use App\Services\EmployeeTypeService;
use Illuminate\Http\Request;
class EmployeesController extends Controller
{
    private $page = 'employees';
    private $service;
    private $types;

    public function __construct(EmployeeService $service, EmployeeTypeService $types) {
        $this->service = $service;
        $this->types = $types;
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
        $data['types'] = $this->types->all();
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
        $data['types'] = $this->types->all();
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
