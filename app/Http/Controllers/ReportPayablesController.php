<?php

namespace App\Http\Controllers;

use App\Services\ReportPayableService;
use Illuminate\Http\Request;
class ReportPayablesController extends Controller
{
    private $page = 'report_payables';
    private $service;

    public function __construct(ReportPayableService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        $meta = $this->service->meta();
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $orders = $this->service->getData($request->customer_id, $request->status, $date);
        $data =  array_merge($meta, ['payables' => $orders]);
        return view('pages.'.$this->page.'.index', $data);
    }

    public function print() {
        return view('pages.'.$this->page.'.print',$this->service->find());
    }
}
