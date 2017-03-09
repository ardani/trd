/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tOrderDetails = $('#table-orders-details');
    var purchase_price = $('#purchase_price');
    $('#save-btn').click(function (e) {
        var suppId = sSupplier.val();
        var qty = $('#qty');
        var length = $('#length');
        var height = $('#height');
        var width = $('#width');
        if (!suppId) {
            alert('supplier belum dipilih');
            return false;
        }
        if (!sProduct.val()) {
            alert('product belum dipilih');
            return false;
        }
        if (!qty.val()) {
            alert('Qty masih kosong');
            return false;
        }

        if (!purchase_price.val()) {
            alert('Price masih kosong');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                product_id: sProduct.val(),
                qty: qty.val(),
                L: length.val(),
                H: height.val(),
                W: width.val(),
                purchase_price: purchase_price.val(),
                _token: Laravel.csrfToken,
                no: $('#no-order').val()
            },
            success: function (data) {
                tOrderDetails.find('tbody').loadTemplate("#row-order", data);
                sProduct.selectpicker('refresh');
                qty.val('');
                calculateTotal(tOrderDetails);
            }
        }).fail(function () {
            alert('Add Order Product Error. Try Again Later');
        })
    });

    $('#count-btn').click(function (e) {
        var total = parseInt(numeral($('#total').text()).value());
        $('#total').text(numeral(total).format('0,0'));
    });

    $('#calculate-btn').click(function (e) {
        calculateTotal(tOrderDetails);
    });

    $('#pay-btn').click(function (e) {
        var total = parseInt(numeral($('#total').text()).value());
        var cash = parseInt($('#cash').val());
    });

    tOrderDetails.on('keypress', '.qty-input', function (e) {
        if (e.which == 13) {
            var self = $(this);
            $.ajax({
                type: 'POST',
                url: self.data('url'),
                data: {
                    product_id: self.data('id'),
                    qty: self.val(),
                    purchase_price: self.data('purchase_price'),
                    _token: Laravel.csrfToken,
                    is_edit: 1,
                    no: $('#no-order').val()
                },
                success: function (data) {
                    tOrderDetails.find('tbody').loadTemplate("#row-order", data);
                    calculateTotal(tOrderDetails);
                }
            }).fail(function () {
                alert('Update Order Product Error. Try Again Later');
            })
        }
    });

    tOrderDetails.on('click', 'a.act-delete', function (e) {
        var product_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {no: $('#no-order').val(), product_id: product_id, _token: Laravel.csrfToken},
            success: function (data) {
                tOrderDetails.find('tbody').loadTemplate("#row-order", data);
                calculateTotal(tOrderDetails);
            }
        }).fail(function () {
            alert('delete row failed');
        })
    });

    $('#save-order-btn').click(function (e) {
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-order').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save Order success');
            }
        }).fail(function () {
            alert('Save Order Error. Try Again Later');
        })
    });

});