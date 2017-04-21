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

    function calculateTotal(el) {
        var sum = 0;
        var charge = 0;
        var cash = $('#cash');
        el.find('tbody tr').each(function () {
            var subtotal = numeral($(this).find('.subtotal').text()).value();
            sum += parseFloat(subtotal);
        });
        $('#total').text(numeral(sum).format('0,0'));
        $('#afterDisc').text(numeral(sum).format('0,0'));
        $('#charge').text('0');
        if (cash.val()) {
            charge = parseFloat(cash.val()) - sum
            $('#charge').text(numeral(charge).format('0,0'));
        }
    }


    $('#save-btn').click(function (e) {
        var suppId = sSupplier.val();
        if (!suppId) {
            alert('supplier belum dipilih');
            return false;
        }
        if (!sProductRaw.val()) {
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
                product_id: sProductRaw.val(),
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
                sProductRaw.selectpicker('refresh');
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

    $('#cash').keyup(function (e) {
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
                    attribute: self.data('attribute'),
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
        var url = $(this).data('redirect');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-order').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save Order success');
                window.location.replace(url);
            }
        }).fail(function () {
            alert('Save Order Error. Try Again Later');
        })
    });

    sProductRaw.on('changed.bs.select', function (e) {
        var units = $(this).find(':selected').data();
        var html = '';
        if (Object.keys(units).length == 2) {
            unitsWrapper.html(html);
            return;
        }

        Object.keys(units).forEach(function (key) {
            if (key == 'sellingprice') {
                return true;
            }

            html += '<div class="col-md-4"> ' +
                '<label class="form-control-label">'+key.toUpperCase()+'('+units[key]+')</label> ' +
                '<input data-unit="'+units[key]+'" type="number" id="'+key+'" class="form-control " value="1" required></div>';
        })
        unitsWrapper.html(html);

        // set selling price
        var sellingPrice = $(this).find(':selected').data('sellingprice');
        $('#selling_price').val(sellingPrice);
    });

});