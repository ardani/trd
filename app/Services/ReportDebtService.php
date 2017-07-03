<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Entrust;

class ReportDebtService extends Service {

    protected $model;
    protected $name = 'report_debts';

    public function __construct(Order $model) {
        $this->model = $model;
    }

    public function getData($supplier = 0, $status = 0, $date = '') {
        $result = $this->model->where(function ($query) use ($supplier, $date, $status) {
            if ($supplier) {
                $query->where('supplier_id', $supplier);
            }
            if ($status) {
                $query->where('paid_status', $status);
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