/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tOrderDetails = $('#table-orders-details');
    var purchase_price = $('#purchase_price');
    var selling_price = $('#selling_price');
    var unitsWrapper = $('#units');
    var qty = $('#qty');

    function resetForm() {
        selling_price.val('');
        purchase_price.val('');
        qty.val('');
    }

    $('#save-btn').click(function (e) {
        var suppId = sSupplier.val();
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

        if (!purchase_price.val() || !selling_price.val()) {
            alert('Price masih kosong');
            return false;
        }

        if (+purchase_price.val() > +selling_price.val()) {
            alert('Purchase price cant more than selling price');
            return false;
        }

        var units = [];
        var vP = 1;
        var vL = 1;
        var vT = 1;
        if ($('#p').length) {
            vP = $('#p').val();
            units.push(vP + $('#p').data('unit'));
        }

        if ($('#l').length) {
            vL = $('#l').val();
            units.push(vL + $('#l').data('unit'));
        }

        if ($('#t').length) {
            vT = $('#t').val();
            units.push(vT + $('#t').data('unit'));
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                product_id: sProduct.val(),
                qty: qty.val(),
                attribute: vT*vL*vP,
                units: units.join('x'),
                purchase_price: purchase_price.val(),
                selling_price: selling_price.val(),
                _token: Laravel.csrfToken,
                no: $('#no-order').val()
            },
            success: function (data) {
                tOrderDetails.find('tbody').loadTemplate("#row-order", data);
                sProduct.selectpicker('refresh');
                resetForm();
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
                    selling_price: self.data('selling_price'),
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
        if (!confirm('Are you sure delete this data?')) {
            return;
        }
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

    sProduct.on('changed.bs.select', function (e) {
        var units = $(this).find(':selected').data();
        var html = '';
        if (Object.keys(units).length == 1) {
            unitsWrapper.html(html);
            return;
        }

        Object.keys(units).forEach(function (key) {
            html += '<div class="col-md-4"> ' +
                '<label class="form-control-label">'+key.toUpperCase()+'('+units[key]+')</label> ' +
                '<input data-unit="'+units[key]+'" type="number" id="'+key+'" class="form-control " value="1" required></div>';
        })
        unitsWrapper.html(html);
    });

});