<?php
namespace App\Observers;
use App\Models\Production;
use App\Models\PurchaseOrder;

/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 2/19/17
 * Time: 9:44 AM
 */


class PurchaseOrderObserver {
    public function saved(PurchaseOrder $purchase_order) {
        if (!$exist = Production::where('purchase_order_id',$purchase_order->id)->first()){
             Production::create([
                'purchase_order_id' => $purchase_order->id,
                'no' => auto_number_productions()
            ]);
        }
    }
}