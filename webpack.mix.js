const {mix} = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.combine([
    'resources/assets/css/lib/datatables-net/datatables.min.css',
    'resources/assets/css/separate/vendor/datatables-net.min.css',
    'resources/assets/css/separate/vendor/lobipanel.css',
    'resources/assets/css/lib/jqueryui/jquery-ui.min.css',
    'resources/assets/css/separate/pages/widgets.min.css',
    'resources/assets/css/lib/font-awesome/font-awesome.min.css',
    'resources/assets/css/lib/bootstrap/bootstrap.min.css',
    'resources/assets/css/separate/vendor/bootstrap-select/bootstrap-select.min.css',
    'resources/assets/css/separate/vendor/bootstrap-daterangepicker.min.css',
    'resources/assets/css/main.css',
    'resources/assets/css/custom.css'
], 'public/css/default.css')
    .combine(['resources/assets/css/separate/pages/login.min.css'], 'public/css/login.min.css')
    .combine(['resources/assets/css/separate/pages/error.min.css'], 'public/css/error.min.css')
    .combine([
        'resources/assets/js/lib/jquery/jquery.min.js',
        'resources/assets/js/lib/tether/tether.min.js',
        'resources/assets/js/lib/bootstrap/bootstrap.min.js',
        'resources/assets/js/lib/match-height/jquery.matchHeight.min.js',
        'resources/assets/js/lib/html5-form-validation/jquery.validation.min.js',
        'resources/assets/js/lib/datatables-net/datatables.min.js',
        'resources/assets/js/lib/moment/moment-with-locales.min.js',
        'resources/assets/js/lib/daterangepicker/daterangepicker.js',
        'resources/assets/js/lib/bootstrap-select/bootstrap-select.min.js',
        'resources/assets/js/lib/bootstrap-select/ajax-bootstrap-select.min.js',
        'resources/assets/js/lib/jquery.loadTemplate.min.js',
        'resources/assets/js/lib/numeral.min.js',
        'resources/assets/js/plugins.js',
        'resources/assets/js/app.js',
        'resources/assets/js/custom.js'
    ], 'public/js/main.js')
    .copy('resources/assets/js/sale-orders.js', 'public/js/sale-orders.js')
    .copy('resources/assets/js/payment-order.js', 'public/js/payment-order.js')
    .copy('resources/assets/js/payment-sale.js', 'public/js/payment-sale.js')
    .copy('resources/assets/js/orders.js', 'public/js/orders.js')
    .copy('resources/assets/js/productions.js', 'public/js/productions.js')
    .copy('resources/assets/js/return-orders.js', 'public/js/return-orders.js')
    .copy('resources/assets/js/return-sales.js', 'public/js/return-sales.js')
    .copy('resources/assets/js/report-debt.js', 'public/js/report-debt.js')
    .copy('resources/assets/js/report-sales.js', 'public/js/report-sales.js')
    .copy('resources/assets/js/report-profits.js', 'public/js/report-profits.js')
    .copy('resources/assets/js/report-payables.js', 'public/js/report-payables.js')
    .copy('resources/assets/js/cashins.js', 'public/js/cashins.js')
    .copy('resources/assets/js/cashouts.js', 'public/js/cashouts.js')
    .copy('resources/assets/js/request-products.js', 'public/js/request-products.js')
    .copy('resources/assets/js/take-product.js', 'public/js/take-product.js')
    .copy('resources/assets/js/correction-stock.js', 'public/js/correction-stock.js');