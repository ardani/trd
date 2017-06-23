<?php

namespace App\Http\Controllers;

use App\Services\ReportDebtService;
use Illuminate\Http\Request;
class ReportSalesController extends Controller
{
    private $page = 'report_sales';
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

    public function doPrint(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $data['orders'] = $this->service->getData($request->supplier_id, $request->status, $date);
        switch ($request->type) {
            case 'normal':
                return view('pages.'.$this->page.'.print', $data);
                break;
            case 'excel':

                break;
        }
    }
}
