<?php

namespace App\Http\Controllers;

use App\Services\ComponentUnitService;
use App\Services\UnitService;
use Illuminate\Http\Request;
class ComponentUnitsController extends Controller
{
    private $page = 'component_units';
    private $service;
    private $unit;

    public function __construct(ComponentUnitService $service, UnitService $unit) {
        $this->service = $service;
        $this->unit = $unit;
    }

    public function index($unit_id) {
        if (request()->ajax()) {
            return $this->service->datatables(['unit_id' => $unit_id]);
        }
        $data = $this->service->meta();
        $data['unit'] = $this->unit->find($unit_id);
        return view('pages.'.$this->page.'.index',$data);
    }

    public function show($unit_id,$id) {
        return view('pages.'.$this->page.'.show',$this->service->find($id));
    }

    public function create($unit_id) {
        $data = $this->service->meta();
        $data['unit'] = $this->unit->find($unit_id);
        return view('pages.'.$this->page.'.create',$data);
    }

    public function store(Request $request,$unit_id) {
        $data = $request->all();
        $this->service->store($data);
        return redirect()->back()->with('message','Save Success');
    }

    public function edit($unit_id,$id) {
        $model = $this->service->find($id);
        $data = $this->service->meta();
        $data['id'] = $id;
        $data['model'] = $model;
        $data['unit'] = $this->unit->find($unit_id);
        return view('pages.'.$this->page.'.edit', $data);
    }

    public function update(Request $request, $unit_id, $id) {
        $data = $request->all();
        $this->service->update($data,$id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($unit_id,$id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }
}
