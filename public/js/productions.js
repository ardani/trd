/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tProductionDetails = $('#table-productions-details');

    $('#save-btn').click(function (e) {
        var qty = $('#qty');
        if (!sProduct.val()) {
            alert('product belum dipilih');
            return false;
        }
        if (!qty.val()) {
            alert('Qty masih kosong');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                product_id: sProduct.val(),
                qty: qty.val(),
                _token: Laravel.csrfToken,
                no: $('#no-production').val()
            },
            success: function (data) {
                tProductionDetails.find('tbody').loadTemplate("#row-production", data);
                sProduct.selectpicker('refresh');
                qty.val('');
                calculateTotal(tProductionDetails);
            }
        }).fail(function () {
            alert('Add Production Product Error. Try Again Later');
        })
    });

    tProductionDetails.on('keypress', '.qty-input', function (e) {
        if (e.which == 13) {
            var self = $(this);
            $.ajax({
                type: 'POST',
                url: self.data('url'),
                data: {
                    product_id: self.data('id'),
                    qty: self.val(),
                    _token: Laravel.csrfToken,
                    is_edit: 1,
                    no: $('#no-production').val()
                },
                success: function (data) {
                    tProductionDetails.find('tbody').loadTemplate("#row-production", data);
                    calculateTotal(tProductionDetails);
                }
            }).fail(function () {
                alert('Add Production Product Error. Try Again Later');
            })
        }
    });

    tProductionDetails.on('click', 'a.act-delete', function (e) {
        var product_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {no: $('#no-production').val(), product_id: product_id, _token: Laravel.csrfToken},
            success: function (data) {
                tProductionDetails.find('tbody').loadTemplate("#row-production", data);
                calculateTotal(tProductionDetails);
            }
        }).fail(function () {
            alert('delete row failed');
        })
    });

    $('#save-production-btn').click(function (e) {
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-production').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save Production success');
            }
        }).fail(function () {
            alert('Save Production Error. Try Again Later');
        })
    });
});