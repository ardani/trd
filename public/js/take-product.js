/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var unitsWrapper = $('#units');
    sProductRaw.on('changed.bs.select', function (e) {
        var units = $(this).find(':selected').data();
        var html = '';

        Object.keys(units).forEach(function (key) {
            if (key == 'sellingprice') {
              return true;
            }

            html += '<div class="col-md-4"> ' +
                '<label class="form-control-label">' + key.toUpperCase() + '(' + units[key] + ')</label> ' +
                '<input data-unit="' + units[key] + '" type="number" name="attribute[]" id="' + key + '" class="form-control " value="1" required></div>' +
                '<input type="hidden" name="units[]" class="form-control " value="' + units[key] + '" required></div>';
        })
        unitsWrapper.html(html);
    });
});