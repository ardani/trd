<?php

namespace App\Http\Controllers;

use App\Services\AccountCodeService;
use Illuminate\Http\Request;
class AccountCodesController extends Controller
{
    private $page = 'account_codes';
    private $service;

    public function __construct(AccountCodeService $service) {
        $this->service = $service;
    }

    public function index() {
        $meta = $this->service->meta();
        $data['codes'] = $this->service->all();
        $data['saldo'] = $this->service->getData();
        $data = array_merge($meta, $data);
        return view('pages.'.$this->page.'.index', $data);
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

    public function load() {
        $q = request()->input('q');
        if ($q) {
            $where =  function($query) use ($q){
                $query->where('name','like','%'.$q.'%')->orWhere('id','like','%'.$q.'%');
            };
            $data = $this->service->filter($where,20);
            return $data->map(function($val,$key) {
                return [
                    'value' => $val->id,
                    'text' => $val->id.' - '.$val->name,
                ];
            })->toArray();
        }
        return [];
    }
}
