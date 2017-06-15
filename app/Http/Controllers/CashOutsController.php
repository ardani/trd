<?php

namespace App\Http\Controllers;

use App\Services\AccountCodeService;
use App\Services\CashOutService;
use Illuminate\Http\Request;
class CashOutsController extends Controller
{
    private $page = 'cash_outs';
    private $service;
    private $account;

    public function __construct(CashOutService $service, AccountCodeService $account) {
        $this->service = $service;
        $this->account = $account;
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
        $data['value'] = $data['value'] * -1;
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
        $data['value'] = $data['value'] * -1;
        $this->service->update($data, $id);
        return redirect()->back()->with('message','Update Success');
    }

    public function delete($id) {
        $deleted = $this->service->delete($id);
        return ['status' => $deleted];
    }

    public function doPrint(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $data['cashs'] = $this->service->getData($date);
        return view('pages.'.$this->page.'.print', $data);
    }
}
