/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var unitsWrapper = $('#units');
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
                '<input data-unit="' + units[key] + '" type="number" name="units[]" id="' + key + '" class="form-control " value="1" required></div>';
        })
        unitsWrapper.html(html);
    });
});