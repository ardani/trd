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
    }
});

// custom
Route::group(['middleware' => 'auth'], function () {
    Route::get('customers/ajaxs/load','CustomersController@load');
    Route::get('suppliers/ajaxs/load','SuppliersController@load');
    Route::get('products/units/{id}','ProductsController@loadUnit');
    Route::get('products/ajaxs/load','ProductsController@load');
    Route::get('account_codes/ajaxs/load','AccountCodesController@load');

    // temp create
    Route::post('purchase_orders/actions/addTemp', [
            'middleware' => ['permission:create.purchase_orders'],
            'uses'       => 'PurchaseOrdersController@addTempPODetail'
    ]);
    Route::post('purchase_orders/actions/deleteTemp', [
        'middleware' => ['permission:create.purchase_orders'],
        'uses'       => 'PurchaseOrdersController@deleteTempPODetail'
    ]);
    Route::get('purchase_orders/actions/viewTemp/{no}', [
        'middleware' => ['permission:create.purchase_orders'],
        'uses'       => 'PurchaseOrdersController@viewTempPODetail'
    ]);

    // edit PO
    Route::post('purchase_orders/actions/add', [
    'middleware' => ['permission:create.purchase_orders'],
            'uses'       => 'PurchaseOrdersController@addPODetail'
    ]);
    Route::post('purchase_orders/actions/delete', [
        'middleware' => ['permission:create.purchase_orders'],
        'uses'       => 'PurchaseOrdersController@deletePODetail'
    ]);
    Route::get('purchase_orders/actions/view/{no}', [
        'middleware' => ['permission:create.purchase_orders'],
        'uses'       => 'PurchaseOrdersController@viewPODetail'
    ]);

    // orders
    // temp create
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

    // production
    Route::post('productions/actions/add', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@addPRDetail'
    ]);
    Route::post('productions/actions/delete', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@deletePRDetail'
    ]);
    Route::get('productions/actions/view/{no}', [
        'middleware' => ['permission:create.productions'],
        'uses'       => 'ProductionsController@viewPRDetail'
    ]);

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
});

Route::get('debug', function () {

});