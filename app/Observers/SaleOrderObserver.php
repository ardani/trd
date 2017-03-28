<?php

namespace App\Observers;

use App\Models\Production;
use App\Models\SaleOrder;

/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 2/19/17
 * Time: 9:44 AM
 */


class SaleOrderObserver {
    public function saved(SaleOrder $sale_order) {
        if (!$exist = Production::where('sale_order_id',$sale_order->id)->first()) {
             Production::create([
                'sale_order_id' => $sale_order->id,
                'no' => auto_number_productions()
            ]);
        }
    }
}