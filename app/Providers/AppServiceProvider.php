<?php

namespace App\Providers;

use App\Models\PurchaseOrder;
use App\Observers\PurchaseOrderObserver;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('includes.nav', function ($view) {
            $menus = $this->app->make('App\Services\MenuService');
            $view->with('menus',$menus->buildSideMenu());
        });
        // automatically create production after purchase order saved
        PurchaseOrder::observe(PurchaseOrderObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/helper.php';
    }
}
