<?php

namespace App\Http\Controllers;

use App\Services\CashFlowService;
use App\Services\ReportProfitService;
use App\Services\SaleOrderService;
use Illuminate\Http\Request;
class ReportProfitsController extends Controller
{
    private $page = 'report_profits';
    private $service;
    private $cashflow;

    public function __construct(ReportProfitService $service, CashFlowService $cashFlowService) {
        $this->service = $service;
        $this->cashflow = $cashFlowService;
    }

    public function index(Request $request) {
        $date = $request->date ?: date('t/m/Y');
        $meta = $this->service->meta();
        $data = [
            'date' => $date,
            'sales_total' => $this->service->getTotalSale($date),
            'last_stock' => $this->service->getTotalLastStock($date),
            'order_total' => $this->service->getTotalOrder($date),
            'production_total' => $this->service->getTotalProduction($date),
            'costs' => $this->service->getCost($date),
            'first_stock' => $this->service->getTotalFirstStock($date),
            'profit' => 0
        ];

        $data =  array_merge($meta, $data);
        return view('pages.'.$this->page.'.index', $data);
    }

    public function doPrint(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $data['sales'] = $this->service->getData($request->customer_id, $date);
        $data['customer'] = $request->customer_id ? $this->customer->find($request->customer_id)->name : 'ALL';
        switch ($request->type) {
            case 'normal':
                return view('pages.'.$this->page.'.print', $data);
                break;
        }
    }
}
