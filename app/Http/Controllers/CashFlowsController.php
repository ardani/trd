<?php

namespace App\Http\Controllers;

use App\Services\AccountCodeService;
use App\Services\CashFlowService;
use Illuminate\Http\Request;
class CashFlowsController extends Controller
{
    private $page = 'cash_flows';
    private $service;
    private $account;

    public function __construct(CashFlowService $service, AccountCodeService $account) {
        $this->service = $service;
        $this->account = $account;
    }

    public function index(Request $request) {
        $meta = $this->service->meta();
        $date = $request->date ?: date('01/m/Y') .' - '.date('t/m/Y');
        $account_code_id = $request->account_code_id ?: null;
        $data['cashes'] = $this->service->getData($account_code_id, $date);
        $data = array_merge($meta, $data);
        if ($request->type == 'print') {
            return view('pages.' . $this->page . '.print', $data);
        }
        return view('pages.' . $this->page . '.index', $data);
    }
}
