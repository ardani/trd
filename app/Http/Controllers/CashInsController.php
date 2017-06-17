<?php

namespace App\Http\Controllers;

use App\Services\AccountCodeService;
use App\Services\CashInService;
use Illuminate\Http\Request;
class CashInsController extends Controller
{
    private $page = 'cash_ins';
    private $service;
    private $account;

    public function __construct(CashInService $service, AccountCodeService $account) {
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
        $meta = $this->service->meta();
        $auto_number = auto_number_cash_in();
        $data['auto_number_cash_in'] = $auto_number;
        $data['transactions'] = session($auto_number);
        $data['cashes'] = $this->account->getCash();
        $total = 0;
        if (session($auto_number)) {
            $total = collect(session($auto_number))->sum(function($val){
                return $val['debit'];
            });
        }
        $data['total'] = $total;
        $data = array_merge($meta, $data);
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
        $data['cashes'] = $this->account->getCash();
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

    public function doPrint(Request $request) {
        $data['cashes'] = $this->service->find($request->id);
        return view('pages.'.$this->page.'.print', $data);
    }

    public function viewTempPODetail($no) {
        return session($no);
    }

    public function addTempPODetail(Request $request) {
        $sessions = session()->has($request->no) ? session($request->no) : [];
        $code = $this->account->find($request->account_code_id);
        $id = md5(time());
        $sessions[$id] = [
            'id'             => $id,
            'account_code_id'=> $request->account_code_id,
            'name'           => $code->name,
            'debit'          => $request->debit,
            'note'           => $request->note
        ];

        session([$request->no => $sessions]);
        return array_values($sessions);
    }

    public function deleteTempPODetail(Request $request) {
        session()->forget($request->no.'.'.$request->id);
        return array_values($this->viewTempPODetail($request->no));
    }

    public function viewPODetail($no) {
        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $transactions = $PO->details->map(function($val,$key){
            return  [
                'id'             => $val->id,
                'account_code_id'=> $val->account_code_id,
                'name'           => $val->account_code->name,
                'debit'          => $val->debit,
                'note'           => $val->note
            ];
        });

        return $transactions->keyBy('id')->toArray();
    }

    public function addPODetail(Request $request) {
        $no = $request->no;
        $transactions = $this->viewPODetail($no);
        $key = md5(time());
        $transactions[ $key ] = [
            'account_code_id'=> $request->account_code_id,
            'debit'          => $request->debit,
            'note'           => $request->note
        ];

        $PO = $this->service->where(function($query) use ($no){
            $query->where('no',$no);
        });

        $PO->details()->create($transactions[ $key ]);
        return array_values($transactions);
    }

    public function deletePODetail(Request $request) {
        $no = $request->no;
        $PO = $this->service->where(function($query) use ($no) {
            $query->where('no',$no);
        });

        $PO->details()->where('id',$request->id)->delete();
        return array_values($this->viewPODetail($no));
    }
}
