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
        $result = $this->model->where(function ($query) use ($customer, $date) {
            if ($customer) {
                $query->where('customer_id', $customer);
            }
            if ($date) {
                $dates = explode(' - ', $date);
                $start_at = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');
                $finish_at = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d');
                $query->whereBetween('created_at', [$start_at, $finish_at]);
            }
            $query->whereNotNull('paid_until_at');
        })->get();

        return $result->filter(function($value) use ($status) {
            if ($status == 1) {
                return $value->payment->total >= $value->total;
            }

            if ($status == 2) {
                return $value->payment->total < $value->total;
            }

            return true;
        });
    }
}