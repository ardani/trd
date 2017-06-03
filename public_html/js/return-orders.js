/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tOrderDetails = $('#table-order-details');
    var tReturnOrderDetails = $('#table-return-order-details');
    tOrderDetails.on('click','a.act-add-return', function(e){
        var qty = $(this).parents('tr').find('.qty-input').val();
        if (parseInt(qty) > parseInt($(this).data('qty'))) {
            alert('qty not valid');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                no: $('#no-ro').val(),
                product_id: $(this).data('id'),
                qty: qty,
                _token: Laravel.csrfToken
            },
            success: function (data) {
                tReturnOrderDetails.find('tbody').loadTemplate("#row-return-order", data);
            }
        }).fail(function () {
            alert('Add Return orders Product Error. Try Again Later');
        })
    });

    tReturnOrderDetails.on('click', 'a.act-return-delete', function (e) {
        var product_id = $(this).data('id');
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                type: 'POST',
                url: $(this).data('url'),
                data: {no: $('#no-rs').val(), product_id: product_id, _token: Laravel.csrfToken},
                success: function (data) {
                    tReturnOrderDetails.find('tbody').loadTemplate("#row-order", data);
                }
            }).fail(function () {
                alert('delete row failed');
            })
        }
        return false;
    });

    $('#save-return-order-btn').click(function (e) {
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-return-orders').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save Return order Success');
            }
        }).fail(function () {
            alert('Save Order Error. Try Again Later');
        })
    });
});