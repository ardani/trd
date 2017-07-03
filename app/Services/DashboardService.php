<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\Order;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Entrust;

class DashboardService extends Service {

    private $order;
    private $sale;
    private $day = 3;

    public function __construct(SaleOrder $saleOrder, Order $order) {
        $this->order = $order;
        $this->sale = $saleOrder;
    }

    public function orderDueDate() {
        $due_date = Carbon::now()->addDays($this->day);
        return $this->order->where('payment_method_id',2)
            ->where('paid_status', 0)
            ->where('paid_until_at','<=',$due_date)
            ->get();

    }

    public function saleDueDate() {
        $due_date = Carbon::now()->addDays($this->day);
        return $this->sale->where('payment_method_id',2)
            ->where('paid_status', 0)
            ->where('paid_until_at','<=', $due_date)
            ->get();
    }
}