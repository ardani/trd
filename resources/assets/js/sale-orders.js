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
        var afterDisc = total - disc;
        $('#afterDisc').text(numeral(afterDisc).format('0,0'));
        $('#charge').text(numeral(cash - afterDisc).format('0,0'));
    }

    $('table').on('click', 'a.act-delete', function (e) {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                no: $('#no-po').val(),
                id: id,
                _token: Laravel.csrfToken
            },
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
        var isCredit = $('input[name=payment_method_id]').prop('checked');
        var cash = $('input[name=cash]').val() ? numeral($('input[name=cash]').val()) : 0;
        var total = $('#total').text() ? numeral($('#total').text()).value() : 0;
        if (!isCredit && cash < total) {
            alert('DP / Cash harus diisi')
            return false;
        }
        $.ajax({
            type: 'POST',
            url: $('#form-sales').data('url'),
            data: $('#form-sales').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function () {
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