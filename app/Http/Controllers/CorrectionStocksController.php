<?php

namespace App\Http\Controllers;

use App\Services\CorrectionStockService;
use Illuminate\Http\Request;
class CorrectionStocksController extends Controller
{
    private $page = 'correction_stocks';
    private $service;

    public function __construct(CorrectionStockService $service) {
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
        $data['units'] = implode('x', $request->units);
        $data['attribute'] = collect($request->units)->reduce(function ($carry, $item) {
            $carry = is_null($carry) ? 1 : $carry;
            return $carry *= $item;
        });
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
}
