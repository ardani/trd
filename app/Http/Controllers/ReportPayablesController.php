<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\ReportPayableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
class ReportPayablesController extends Controller
{
    private $page = 'report_payables';
    private $service;
    private $customer;

    public function __construct(ReportPayableService $service, CustomerService $customerService) {
        $this->service = $service;
        $this->customer = $customerService;
    }

    public function index(Request $request) {
        $meta = $this->service->meta();
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $orders = $this->service->getData($request->customer_id, $request->status, $date);
        $status = [
            'UNPAID', 'PAID'
        ];
        $data =  array_merge($meta, [
            'payables' => $orders,
            'date' => $date,
            'statuses' => $status,
            'status' => $request->status,
            'customer' => $request->customer_id ? $this->customer->find($request->customer_id) : ''
        ]);
        return view('pages.'.$this->page.'.index', $data);
    }

    public function doPrint(Request $request) {
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $data['sales'] = $this->service->getData($request->customer_id, $request->status, $date);
        $data['now'] = Carbon::create(date('Y'), date('m'), date('d'), 0);
        $data['customer'] = $request->customer_id ? $this->customer->find($request->customer_id)->name : 'ALL';
        $status = ['UNPAID', 'PAID'];
        $data['status'] = $status[$request->status];
        switch ($request->type) {
            case 'normal':
                return view('pages.'.$this->page.'.print', $data);
                break;
            case 'excel':

                break;
        }
    }
}
