/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tSaleDetails = $('#table-sale-details');
    var unitsWrapper = $('#units');
    $('#save-btn').click(function (e) {
        var custTypeId = sCustomer.find('option:selected').data('customer_type_id');
        var qty = $('#qty');
        if (!custTypeId) {
            alert('customer belum dipilih');
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
                desc:  $('#desc').val(),
                attribute: vP * vL * vT,
                units: units.join('x'),
                customer_type_id: custTypeId,
                _token: Laravel.csrfToken,
                no: $('#no-po').val()
            },
            success: function (data) {
                tSaleDetails.find('tbody').loadTemplate("#row-sale", data);
                sProduct.selectpicker('refresh');
                qty.val('');
                calculateTotal(tSaleDetails);
                chargeCalculation()
            }
        }).fail(function () {
            alert('Add Purchase Product Error. Try Again Later');
        })
    });

    $('#disc,#cash').keyup(function () {
        chargeCalculation();
    });

    function chargeCalculation() {
        var total = parseFloat(numeral($('#total').text()).value());
        var cash = $('#cash').val() ? parseFloat($('#cash').val()) : 0;
        var disc = $('#disc').val() ? parseFloat($('#disc').val()) : 0;
        var afterDisc = total-disc;
        $('#afterDisc').text(numeral(afterDisc).format('0,0'));
        $('#charge').text(numeral(cash - afterDisc).format('0,0'));
    }

    $('table').on('keypress', '.qty-input', function (e) {
        if (e.which == 13) {
            var custTypeId = sCustomer.find('option:selected').data('customer_type_id');
            var self = $(this);
            $.ajax({
                type: 'POST',
                url: self.data('url'),
                data: {
                    product_id: self.data('id'),
                    qty: self.val(),
                    customer_type_id: custTypeId,
                    _token: Laravel.csrfToken,
                    is_edit: 1,
                    no: $('#no-po').val()
                },
                success: function (data) {
                    tSaleDetails.find('tbody').loadTemplate("#row-sale", data);
                    calculateTotal(tSaleDetails);
                    chargeCalculation();
                }
            }).fail(function () {
                alert('Update Purchase Product Error. Try Again Later');
            })
        }
    });

    $('table').on('click', 'a.act-delete', function (e) {
        var product_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {no: $('#no-po').val(), product_id: product_id, _token: Laravel.csrfToken},
            success: function (data) {
                tSaleDetails.find('tbody').loadTemplate("#row-sale", data);
                calculateTotal(tSaleDetails);
                chargeCalculation();
            }
        }).fail(function () {
            alert('delete row failed');
        })
    });

    $('#save-sale-btn').click(function (e) {
        var url = $(this).data('redirect');
        $.ajax({
            type: 'POST',
            url: $('#form-sales').data('url'),
            data: $('#form-sales').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save PO success');
                window.location.replace(url);
            }
        }).fail(function () {
            alert('Save PO Error. Try Again Later');
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
                '<label class="form-control-label">' + key.toUpperCase() + '(' + units[key] + ')</label> ' +
                '<input data-unit="' + units[key] + '" type="number" id="' + key + '" class="form-control " value="1" required></div>';
        })
        unitsWrapper.html(html);
    });
});