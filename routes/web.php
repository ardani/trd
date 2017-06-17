<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('debug', function(){
    echo auto_number_product('Design 1231');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::get('register', 'RegisterController@showRegistrationForm')->middleware(['view.register'])->name('register');
    Route::post('register', 'RegisterController@register');
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm');
    Route::post('password/reset', 'ResetPasswordController@reset');
});
Route::get('/', 'DashboardController@index')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
    foreach (app('App\Services\MenuService')->menuRoute() as $menu) {
        Route::get($menu->path, [
            'middleware' => ['permission:view.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@index'
        ]);
        Route::get($menu->path . '/show/{id}', [
            'middleware' => ['permission:view.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@show'
        ]);
        Route::get($menu->path . '/create', [
            'middleware' => ['permission:create.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@create'
        ]);
        Route::post($menu->path . '/create', [
            'middleware' => ['permission:create.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@store'
        ]);
        Route::get($menu->path . '/edit/{id}', [
            'middleware' => ['permission:edit.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@edit'
        ]);
        Route::post($menu->path . '/edit/{id}', [
            'middleware' => ['permission:edit.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@update'
        ]);
        Route::post($menu->path . '/delete/{id}', [
            'middleware' => ['permission:delete.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@delete'
        ]);
        Route::get($menu->path . '/print', [
            'middleware' => ['permission:view.' . $menu->path],
            'uses'       => studly_case($menu->path) . 'Controller@doPrint'
        ]);
    }
});

// custom
Route::group(['middleware' => 'auth'], function () {
    Route::get('profile', 'DashboardController@profile');
    Route::post('profile', 'DashboardController@updateProfile');
    Route::get('customers/ajaxs/load','CustomersController@load');
    Route::get('suppliers/ajaxs/load','SuppliersController@load');
    Route::get('products/units/{id}','ProductsController@loadUnit');
    Route::get('products/ajaxs/load','ProductsController@load');
    Route::get('products/ajaxs/load_raw','ProductsController@loadRaw');
    Route::get('products/ajaxs/load_production','ProductsController@loadProduction');
    Route::get('account_codes/ajaxs/load','AccountCodesController@load');
    Route::get('sale_orders/ajaxs/load','SaleOrdersController@load');
    Route::get('sale_orders/ajaxs/detail','SaleOrdersController@detail');
    Route::get('orders/ajaxs/load','OrdersController@load');
    Route::get('orders/ajaxs/detail','OrdersController@detail');
    // temp create
    Route::post('sale_orders/actions/addTemp', [
            'middleware' => ['permission:create.sale_orders'],
            'uses'       => 'SaleOrdersController@addTempPODetail'
    ]);
    Route::post('sale_orders/actions/deleteTemp', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@deleteTempPODetail'
    ]);
    Route::get('sale_orders/actions/viewTemp/{no}', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@viewTempPODetail'
    ]);
    // edit Sale Order
    Route::post('sale_orders/actions/add', [
    'middleware' => ['permission:create.sale_orders'],
            'uses'       => 'SaleOrdersController@addPODetail'
    ]);
    Route::post('sale_orders/actions/delete', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@deletePODetail'
    ]);
    Route::get('sale_orders/actions/view/{no}', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@viewPODetail'
    ]);
    Route::get('sale_orders/actions/print/nota/{no}', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@printNota'
    ]);
    Route::get('sale_orders/actions/print/invoice/{no}', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@printInvoice'
    ]);
    Route::get('sale_orders/actions/print/do/{no}', [
        'middleware' => ['permission:create.sale_orders'],
        'uses'       => 'SaleOrdersController@printDo'
    ]);
    // return sales
    Route::post('return_sale_orders/actions/addTemp', [
        'middleware' => ['permission:create.return_sale_orders'],
        'uses'       => 'ReturnSaleOrdersController@addTempDetail'
    ]);
    Route::post('return_sale_orders/actions/deleteTemp', [
        'middleware' => ['permission:create.return_sale_orders'],
        'uses'       => 'ReturnSaleOrdersController@deleteTempDetail'
    ]);
    Route::post('return_sale_orders/actions/add', [
        'middleware' => ['permission:create.return_sale_orders'],
        'uses'       => 'ReturnSaleOrdersController@addDetail'
    ]);
    Route::post('return_sale_orders/actions/delete', [
        'middleware' => ['permission:create.return_sale_orders'],
        'uses'       => 'ReturnSaleOrdersController@deleteDetail'
    ]);
    Route::post('return_sale_orders/actions/complete/:id', [
        'middleware' => ['permission:update.return_sale_orders'],
        'uses'       => 'ReturnSaleOrdersController@complete'
    ]);
    // orders
    Route::post('orders/actions/addTemp', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@addTempPODetail'
    ]);
    Route::post('orders/actions/deleteTemp', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@deleteTempPODetail'
    ]);
    Route::get('orders/actions/viewTemp/{no}', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@viewTempPODetail'
    ]);
    // edit Orders
    Route::post('orders/actions/add', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@addPODetail'
    ]);
    Route::post('orders/actions/delete', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@deletePODetail'
    ]);
    Route::get('orders/actions/view/{no}', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@viewPODetail'
    ]);
    Route::get('orders/actions/print/invoice/{no}', [
        'middleware' => ['permission:create.orders'],
        'uses'       => 'OrdersController@printInvoice'
    ]);
    // return orders
    Route::post('return_orders/actions/addTemp', [
        'middleware' => ['permission:create.return_orders'],
        'uses'       => 'ReturnOrdersController@addTempDetail'
    ]);
    Route::post('return_orders/actions/deleteTemp', [
        'middleware' => ['permission:create.return_orders'],
        'uses'       => 'ReturnOrdersController@deleteTempDetail'
    ]);
    Route::post('return_orders/actions/add', [
        'middleware' => ['permission:create.return_orders'],
        'uses'       => 'ReturnOrdersController@addDetail'
    ]);
    Route::post('return_orders/actions/delete', [
        'middleware' => ['permission:create.return_orders'],
        'uses'       => 'ReturnOrdersController@deleteDetail'
    ]);
    Route::post('return_orders/actions/complete/:id', [
        'middleware' => ['permission:update.return_orders'],
        'uses'       => 'ReturnOrdersController@complete'
    ]);
    // production
    Route::post('productions/actions/add', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@addPRDetail'
    ]);
    Route::post('productions/actions/delete', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@deletePRDetail'
    ]);
    Route::get('productions/actions/finish/{id}', [
        'middleware' => ['permission:edit.productions'],
        'uses'       => 'ProductionsController@finished'
    ]);
    Route::get('productions/actions/complete/{id}', [
        'middleware' => ['permission:edit.productions'],
        'uses'       => 'ProductionsController@completed'
    ]);
    Route::get('productions/actions/spk/{id}', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@spk'
    ]);
    Route::get('productions/actions/detail', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@PRDetail'
    ]);
    // product
    Route::get('products/prices/{product_id}', [
        'middleware' => ['permission:view.products'],
        'uses'       => 'ProductPricesController@index'
    ]);
    Route::get('products/prices/{product_id}/create', [
        'middleware' => ['permission:create.products'],
        'uses'       => 'ProductPricesController@create'
    ]);
    Route::post('products/prices/{product_id}/create', [
        'middleware' => ['permission:create.products'],
        'uses'       => 'ProductPricesController@store'
    ]);
    Route::get('products/prices/{product_id}/edit/{id}', [
        'middleware' => ['permission:edit.products'],
        'uses'       => 'ProductPricesController@edit'
    ]);
    Route::post('products/prices/{product_id}/edit/{id}', [
        'middleware' => ['permission:edit.products'],
        'uses'       => 'ProductPricesController@update'
    ]);
    Route::post('products/prices/{product_id}/delete/{id}', [
        'middleware' => ['permission:delete.products'],
        'uses'       => 'ProductPricesController@delete'
    ]);
    // units
    Route::get('units/components/{unit_id}', [
        'middleware' => ['permission:view.units'],
        'uses'       => 'ComponentUnitsController@index'
    ]);
    Route::get('units/components/{unit_id}/edit/{id}', [
        'middleware' => ['permission:edit.units'],
        'uses'       => 'ComponentUnitsController@edit'
    ]);
    Route::post('units/components/{unit_id}/edit/{id}', [
        'middleware' => ['permission:edit.units'],
        'uses'       => 'ComponentUnitsController@update'
    ]);
    Route::get('units/components/{unit_id}/create', [
        'middleware' => ['permission:create.units'],
        'uses'       => 'ComponentUnitsController@create'
    ]);
    Route::post('units/components/{unit_id}/create', [
        'middleware' => ['permission:create.units'],
        'uses'       => 'ComponentUnitsController@store'
    ]);
    Route::post('units/components/{unit_id}/delete/{id}', [
        'middleware' => ['permission:delete.units'],
        'uses'       => 'ComponentUnitsController@delete'
    ]);
    Route::get('roles/permissions/{id}', [
        'middleware' => ['permission:edit.roles'],
        'uses'       => 'RolesController@hasPermission'
    ]);
    Route::post('roles/permissions/{id}', [
        'middleware' => ['permission:edit.roles'],
        'uses'       => 'RolesController@AttachPermission'
    ]);
    // payment
    Route::get('payment_orders/detail/{payment_id}', [
        'middleware' => ['permission:edit.payment_orders'],
        'uses'       => 'PaymentOrdersController@detail'
    ]);
    Route::get('payment_orders/detail/{payment_id}/create', [
        'middleware' => ['permission:create.payment_orders'],
        'uses'       => 'PaymentOrdersController@create'
    ]);
    Route::post('payment_orders/detail/{id}/create', [
        'middleware' => ['permission:create.payment_orders'],
        'uses'       => 'PaymentOrdersController@store'
    ]);
    Route::get('payment_orders/detail/{payment_id}/edit/{id}', [
        'middleware' => ['permission:edit.payment_orders'],
        'uses'       => 'PaymentOrdersController@edit'
    ]);
    Route::post('payment_orders/detail/{payment_id}/edit/{id}', [
        'middleware' => ['permission:edit.payment_orders'],
        'uses'       => 'PaymentOrdersController@update'
    ]);
    Route::post('payment_orders/detail/{payment_id}/delete/{id}', [
        'middleware' => ['permission:delete.payment_orders'],
        'uses'       => 'PaymentOrdersController@delete'
    ]);
    Route::get('payment_orders/actions/print/{no}', [
        'middleware' => ['permission:create.payment_orders'],
        'uses'       => 'PaymentOrdersController@printPayment'
    ]);

    Route::get('payment_sales/detail/{id}', [
        'middleware' => ['permission:edit.payment_sales'],
        'uses'       => 'PaymentSalesController@detail'
    ]);
    Route::get('payment_sales/detail/{id}/create', [
        'middleware' => ['permission:create.payment_sales'],
        'uses'       => 'PaymentSalesController@create'
    ]);
    Route::post('payment_sales/detail/{id}/create', [
        'middleware' => ['permission:create.payment_sales'],
        'uses'       => 'PaymentSalesController@store'
    ]);
    Route::get('payment_sales/detail/{payment_id}/edit/{id}', [
        'middleware' => ['permission:edit.payment_sales'],
        'uses'       => 'PaymentSalesController@edit'
    ]);
    Route::post('payment_sales/detail/{payment_id}/edit/{id}', [
        'middleware' => ['permission:edit.payment_sales'],
        'uses'       => 'PaymentSalesController@update'
    ]);
    Route::post('payment_sales/detail/{payment_id}/delete/{id}', [
        'middleware' => ['permission:delete.payment_sales'],
        'uses'       => 'PaymentSalesController@delete'
    ]);
    Route::get('payment_sales/actions/print/{no}', [
        'middleware' => ['permission:create.payment_sales'],
        'uses'       => 'PaymentSalesController@printPayment'
    ]);
    // request products
    Route::post('request_products/actions/addTemp', [
        'middleware' => ['permission:create.request_products'],
        'uses'       => 'RequestProductsController@addTempPODetail'
    ]);
    Route::post('request_products/actions/deleteTemp', [
        'middleware' => ['permission:create.request_products'],
        'uses'       => 'RequestProductsController@deleteTempPODetail'
    ]);
    Route::get('request_products/actions/viewTemp/{no}', [
        'middleware' => ['permission:create.request_products'],
        'uses'       => 'RequestProductsController@viewTempPODetail'
    ]);
    Route::post('request_products/actions/add', [
        'middleware' => ['permission:create.request_products'],
        'uses'       => 'RequestProductsController@addPODetail'
    ]);
    Route::post('request_products/actions/delete', [
        'middleware' => ['permission:create.request_products'],
        'uses'       => 'RequestProductsController@deletePODetail'
    ]);
    Route::get('request_products/actions/view/{no}', [
        'middleware' => ['permission:create.request_products'],
        'uses'       => 'RequestProductsController@viewPODetail'
    ]);

    Route::post('cash_ins/actions/addTemp', [
        'middleware' => ['permission:create.cash_ins'],
        'uses'       => 'CashinsController@addTempPODetail'
    ]);
    Route::post('cash_ins/actions/deleteTemp', [
        'middleware' => ['permission:create.cash_ins'],
        'uses'       => 'CashinsController@deleteTempPODetail'
    ]);
    Route::get('cash_ins/actions/viewTemp/{no}', [
        'middleware' => ['permission:create.cash_ins'],
        'uses'       => 'CashinsController@viewTempPODetail'
    ]);
    Route::post('cash_ins/actions/add', [
        'middleware' => ['permission:create.cash_ins'],
        'uses'       => 'CashinsController@addPODetail'
    ]);
    Route::post('cash_ins/actions/delete', [
        'middleware' => ['permission:create.cash_ins'],
        'uses'       => 'CashinsController@deletePODetail'
    ]);
    Route::get('cash_ins/actions/view/{no}', [
        'middleware' => ['permission:create.cash_ins'],
        'uses'       => 'CashinsController@viewPODetail'
    ]);

    Route::post('cash_outs/actions/addTemp', [
        'middleware' => ['permission:create.cash_outs'],
        'uses'       => 'CashOutsController@addTempPODetail'
    ]);
    Route::post('cash_outs/actions/deleteTemp', [
        'middleware' => ['permission:create.cash_outs'],
        'uses'       => 'CashOutsController@deleteTempPODetail'
    ]);
    Route::get('cash_outs/actions/viewTemp/{no}', [
        'middleware' => ['permission:create.cash_outs'],
        'uses'       => 'CashOutsController@viewTempPODetail'
    ]);
    Route::post('cash_outs/actions/add', [
        'middleware' => ['permission:create.cash_outs'],
        'uses'       => 'CashOutsController@addPODetail'
    ]);
    Route::post('cash_outs/actions/delete', [
        'middleware' => ['permission:create.cash_outs'],
        'uses'       => 'CashOutsController@deletePODetail'
    ]);
    Route::get('cash_outs/actions/view/{no}', [
        'middleware' => ['permission:create.cash_outs'],
        'uses'       => 'CashOutsController@viewPODetail'
    ]);
});