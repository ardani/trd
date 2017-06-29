<?php

namespace App\Http\Controllers;

use App\Services\ReportDebtService;
use App\Services\SupplierService;
use Carbon\Carbon;
use Illuminate\Http\Request;
class ReportDebtsController extends Controller
{
    private $page = 'report_debts';
    private $service;
    private $supplier;

    public function __construct(ReportDebtService $service, SupplierService $supplierService) {
        $this->service = $service;
        $this->supplier = $supplierService;
    }

    public function index(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $meta = $this->service->meta();
        $orders = $this->service->getData($request->supplier_id, $request->status, $date);
        $data =  array_merge($meta, ['debts' => $orders]);
        return view('pages.'.$this->page.'.index', $data);
    }

    public function doPrint(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $status = [
            'ALL',
            'PAID',
            'UNPAID'
        ];
        $data['orders'] = $this->service->getData($request->supplier_id, $request->status, $date);
        $data['supplier'] = $request->supplier_id ? $this->supplier->find($request->supplier_id)->name : 'ALL';
        $data['status'] = $status[$request->status];
        $data['now'] = Carbon::create(date('Y'), date('m'), date('d'), 0);
        switch ($request->type) {
            case 'normal':
                return view('pages.'.$this->page.'.print', $data);
                break;
            case 'excel':

                break;
        }
    }
}
