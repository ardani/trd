/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tSaleDetails = $('#table-sale-details');
    var tReturnSalesDetail = $('#table-return-sale-details');
    tSaleDetails.on('click','a.act-add-return', function(e){
        var qty = $(this).parents('tr').find('.qty-input').val();
        if (parseInt(qty) > parseInt($(this).data('qty'))) {
            alert('qty not valid');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                no: $('#no-rs').val(),
                product_id: $(this).data('id'),
                qty: qty,
                _token: Laravel.csrfToken
            },
            success: function (data) {
                tReturnSalesDetail.find('tbody').loadTemplate("#row-return-sale", data);
            }
        }).fail(function () {
            alert('Add Return Sales Product Error. Try Again Later');
        })
    });

    tReturnSalesDetail.on('click', 'a.act-return-delete', function (e) {
        var id = $(this).data('id');
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                type: 'POST',
                url: $(this).data('url'),
                data: {no: $('#no-rs').val(), id: id, _token: Laravel.csrfToken},
                success: function (data) {
                    tReturnSalesDetail.find('tbody').loadTemplate("#row-order", data);
                }
            }).fail(function () {
                alert('delete row failed');
            })
        }
        return false;
    });

    $('#save-return-sale-btn').click(function (e) {
        var url = $(this).data('redirect');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-return-sales').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save Return Sale Success');
                window.location.replace(url);
            }
        }).fail(function () {
            alert('Save Order Error. Try Again Later');
        })
    });
});