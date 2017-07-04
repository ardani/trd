/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tProductionDetails = $('#table-productions-details');
    var unitsWrapper = $('#units');

    $('#save-btn').click(function (e) {
        var qty = $('#qty');
        var unitsItem = $('.units-item');

        if (!sProductRaw.val()) {
            alert('product belum dipilih');
            return false;
        }

        if (!qty.val()) {
            alert('Qty masih kosong');
            return false;
        }

        var units = [];
        var attribute = 1;
        unitsItem.each(function (index, el) {
          units.push($(el).val() + $(el).data('unit'));
          attribute = attribute * $(el).val();
        });

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                product_id: sProductRaw.val(),
                production_product_id: $('[name=product_selected]').val(),
                qty: qty.val(),
                date: $('input[name=created_at]').val(),
                attribute: attribute,
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

    $('.setFinish').click(function (e) {
        if (confirm('Are you sure finish product?')) {
            var self = $(this);
            $(this).parents('tr').find('.status').text('finish');
            $.ajax({
                type: 'GET',
                url: $(this).data('url'),
                headers: {
                    'X-CSRF-Token': Laravel.csrfToken
                }
            }).done(function () {
                self.hide();
            }).fail(function () {
                alert('Update Status Error. Try Again Later');
            })
        }
    });

    sProductRaw.on('changed.bs.select', function (e) {
        var units = $(this).find(':selected').data();
        var html = '';

        Object.keys(units).forEach(function (key) {
            if (key == 'sellingprice') {
              return true;
            }

            html += '<div class="col-md-4"> ' +
                '<label class="form-control-label">'+key.toUpperCase()+'('+units[key]+')</label> ' +
                '<input type="number" data-unit="'+units[key]+'" id="'+key+'" class="form-control units-item" value="1" required></div>';
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