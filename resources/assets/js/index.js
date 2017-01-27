/**
 * Created by ardani on 1/19/17.
 */
$(document).ready(function() {
    $('.panel').lobiPanel({
        sortable: true
    });
    $('.panel').on('dragged.lobiPanel', function (ev, lobiPanel) {
        $('.dahsboard-column').matchHeight();
    });
});