<?php

namespace App\Http\Controllers;

use App\Services\ReportDebtService;
use Illuminate\Http\Request;
class ReportDebtsController extends Controller
{
    private $page = 'report_debts';
    private $service;

    public function __construct(ReportDebtService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $meta = $this->service->meta();
        $orders = $this->service->getData($request->supplier_id, $request->status, $date);
        $data =  array_merge($meta, ['debts' => $orders]);
        return view('pages.'.$this->page.'.index', $data);
    }

    public function doPrint($id) {
        return view('pages.'.$this->page.'.show',$this->service->find($id));
    }
}
