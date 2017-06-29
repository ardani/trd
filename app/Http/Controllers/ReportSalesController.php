<?php

namespace App\Http\Controllers;

use App\Services\SaleOrderService;
use Illuminate\Http\Request;
class ReportSalesController extends Controller
{
    private $page = 'report_sales';
    private $service;

    public function __construct(SaleOrderService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $meta = $this->service->meta();
        $sales = $this->service->getData($request->customer_id, $date);
        $data =  array_merge($meta, ['sales' => $sales]);
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
            case 'excel':

                break;
        }
    }
}
