/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tRequestDetails = $('#table-request-details');
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
                qty: qty.val(),
                desc:  $('#desc').val(),
                attribute: attribute,
                units: units.join('x'),
                _token: Laravel.csrfToken,
                no: $('#no-po').val()
            },
            success: function (data) {
                tRequestDetails.find('tbody').loadTemplate("#row-sale", data);
                sProductRaw.selectpicker('refresh');
                qty.val('');
            }
        }).fail(function () {
            alert('Add Product Error. Try Again Later');
        })
    });

    $('table').on('keypress', '.qty-input', function (e) {
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
                    no: $('#no-po').val()
                },
                success: function (data) {
                    tRequestDetails.find('tbody').loadTemplate("#row-sale", data);
                }
            }).fail(function () {
                alert('Update Product Error. Try Again Later');
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
                tRequestDetails.find('tbody').loadTemplate("#row-sale", data);
            }
        }).fail(function () {
            alert('delete row failed');
        })
    });

    $('#save-request-btn').click(function (e) {
        var url = $(this).data('redirect');
        $.ajax({
            type: 'POST',
            url: $('#form-request').data('url'),
            data: $('#form-request').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save Success');
                window.location.replace(url);
            }
        }).fail(function () {
            alert('Save Error. Try Again Later');
        })
    });

    sProductRaw.on('changed.bs.select', function (e) {
        var units = $(this).find(':selected').data();
        var html = '';

        Object.keys(units).forEach(function (key) {
            if (key == 'sellingprice') {
                return true;
            }
            html += '<div class="col-md-4"> ' +
                '<label class="form-control-label">' + key.toUpperCase() + '(' + units[key] + ')</label> ' +
                '<input data-unit="' + units[key] + '" type="number" name="units[]" id="' + key + '" class="form-control units-item" value="1" required></div>';
        })
        unitsWrapper.html(html);
    });
});