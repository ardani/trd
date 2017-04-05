/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tProductionDetails = $('#table-productions-details');
    var unitsWrapper = $('#units');

    $('#save-btn').click(function (e) {
        var qty = $('#qty');
        var length = $('#length');
        var height = $('#height');
        var width = $('#width');
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
                production_product_id: $('[name=product_selected]').val(),
                qty: qty.val(),
                attribute: vP * vL * vT,
                units: units.join('x'),
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
                    production_product_id: $('[name=product_selected]').val(),
                    qty: self.val(),
                    _token: Laravel.csrfToken,
                    is_edit: 1,
                    no: $('#no-production').val()
                },
                success: function (data) {
                    tProductionDetails.find('tbody').loadTemplate("#row-production", data);
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
            data: {no: $('#no-production').val(), product_id: product_id, _token: Laravel.csrfToken, production_product_id: $('[name=product_selected]').val()},
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
                '<input type="number" data-unit="'+units[key]+'" id="'+key+'" class="form-control " value="1" required></div>';
        })
        unitsWrapper.html(html);
    });

    $('.setActive').click(function () {
        $('#listItemOrder').find('tbody tr').removeClass('active');
        $(this).parents('tr').addClass('active');
        $('#product-selected').text($(this).data('name'));
        $('[name=product_selected]').val($(this).data('id'));
        $('.form-wrapper').show();
        $.ajax({
            type: 'GET',
            url: $(this).data('url'),
            data: {
                production_product_id: $(this).data('id'),
                no: $('#no-production').val()
            },
            success: function (data) {
                tProductionDetails.find('tbody').loadTemplate("#row-production", data);
            }
        }).fail(function () {
            alert('Get Production Product Error. Try Again Later');
        })
    })
});