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

class ReportProfitService extends Service {

    protected $model;
    protected $name = 'report_profits';

    public function __construct(SaleOrder $model) {
        $this->model = $model;
    }

    public function getTotalSale($date) {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $raw = "(SELECT (selling_price - disc) * abs(qty) * attribute as total, 
                transactionable_id, transactionable_type 
                FROM transactions
                ) T";

        $result = \DB::table('sale_orders')
            ->selectRaw('sum(T.total) as total')
            ->join(\DB::raw($raw), function($join){
                $join->on('T.transactionable_id', '=', 'sale_orders.id')
                    ->where('T.transactionable_type', 'App\Models\SaleOrder');
            })
            ->whereMonth('created_at', '=', $date->month)
            ->whereYear('created_at', '=', $date->year)
            ->first();

        return $result->total;
    }

    public function getTotalProduction($date) {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $result =\DB::table('transactions')
            ->selectRaw('sum(qty * attribute * purchase_price) as total')
            ->where('transactionable_type',  'App\Models\Production')
            ->whereMonth('created_at', '=', $date->month)
            ->whereYear('created_at', '=', $date->year)
            ->first();

        return ($result) ? $result->total : 0;
    }

    public function getTotalOrder($date) {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $raw = "(SELECT round(purchase_price * qty * attribute) as total, 
                transactionable_id, transactionable_type 
                FROM transactions
                ) T";

        $result = \DB::table('orders')
            ->selectRaw('sum(T.total) as total')
            ->join(\DB::raw($raw), function($join){
                $join->on('T.transactionable_id', '=', 'orders.id')
                    ->where('T.transactionable_type', 'App\Models\Order');
            })
            ->whereMonth('created_at', '=', $date->month)
            ->whereYear('created_at', '=', $date->year)
            ->first();

        return $result->total;
    }

    public function getTotalLastStock($date) {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $raw_correction_stock = "(SELECT (qty * attribute * -1) AS qty, product_id FROM 
            correction_stocks WHERE month(created_at) <= '$date->month' 
            AND year(created_at) <= '$date->year') cs";

        $raw_transaction = "(SELECT (qty * attribute) AS qty, product_id FROM 
            transactions WHERE transactionable_type != 'App\\Models\\RequestProduct' 
            AND month(created_at) <= '$date->month' 
            AND year(created_at) <= '$date->year') t";

        $raw_product_history = "(SELECT product_id, purchase_price FROM 
            product_histories WHERE month(created_at) <= '$date->month' 
            AND year(created_at) <= '$date->year' 
            ORDER BY id DESC LIMIT 1) ph";

        $raw_take_product = "(SELECT (qty * attribute * -1) AS qty, product_id FROM 
            take_products WHERE month(created_at) <= '$date->month' 
            AND year(created_at) <= '$date->year') tp";


        $last_stock = \DB::table('products')
            ->selectRaw('sum((ifnull(start_stock, 0) + ifnull(cs.qty, 0) + ifnull(t.qty, 0) + ifnull(tp.qty, 0)) *
                ifnull(ph.purchase_price, products.purchase_price_default)) AS total')
            ->leftJoin(\DB::raw($raw_correction_stock),'cs.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw($raw_transaction),'t.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw($raw_product_history),'ph.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw($raw_take_product),'tp.product_id', '=', 'products.id')
            ->whereIN('category_id', [1,3])
            ->first();

        return ($last_stock) ? $last_stock->total : 0;
    }

    public function getTotalFirstStock($date) {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $first_stock = \DB::table('products')
            ->selectRaw('sum(start_stock * purchase_price_default) as total')
            ->whereMonth('stock_at', '<=', $date->month)
            ->whereYear('stock_at', '<=', $date->year)
            ->first();

        return ($first_stock) ? $first_stock->total : 0;
    }

    public function getCost($date) {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $result = \DB::table('cash_flows')
            ->selectRaw('SUM(debit-credit) as saldo, account_code_id, account_codes.name')
            ->join('account_codes',function($join){
                $join->on('account_codes.id', '=', 'cash_flows.account_code_id')
                    // get beban
                    ->where('account_codes.type', 12);
            })
            ->whereMonth('cash_flows.created_at', '=', $date->month)
            ->whereYear('cash_flows.created_at', '=', $date->year)
            ->groupBy('account_code_id')
            ->get();

        return $result;
    }
}