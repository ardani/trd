/**
 * Created by ardani on 3/28/17.
 */
$(document).ready(function () {
    var sPaymentOrder = $('.select-payment-order');
    sPaymentOrder.selectpicker({liveSearch: true})
        .ajaxSelectPicker({
            ajax: {
                type: 'GET',
                url: '/orders/ajaxs/load'
            },
            locale: {
                emptyTitle: '-'
            },
        });
});