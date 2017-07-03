<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Entrust;

class ReportPayableService extends Service {

    protected $model;
    protected $name = 'report_payables';

    public function __construct(SaleOrder $model) {
        $this->model = $model;
    }

    public function getData($customer = 0, $status = 0, $date = '') {
        $result = $this->model->where(function ($query) use ($customer, $date, $status) {
            if ($customer) {
                $query->where('customer_id', $customer);
            }
            if ($status) {
                $qstatus = $status == 1 ? 1 : 0;
                $query->where('paid_status', $qstatus);
            }
            if ($date) {
                $dates = explode(' - ', $date);
                $start_at = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');
                $finish_at = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d');
                $query->whereBetween('created_at', [$start_at, $finish_at]);
            }
            $query->where('payment_method_id', 2);
        })->get();

        return $result;
    }

    public function getDataDashboard($date = '') {
        $result = $this->model
            ->where('payment_method_id', 2)
            ->where('paid_status', 0)
            ->where('paid_until_at','<=', $date->format('Y-m-d'))
            ->get();

        return $result;
    }
}