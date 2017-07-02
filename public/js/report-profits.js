$(document).ready(function () {
    var freportPayables = $('#freport-profits');
    var bview = $('#bview');
    var bprint = $('#bprint');
    var bexcel = $('#bexcel');
    var typePrint = $('#type-print');

    bview.click(function(e){
        e.preventDefault();
        freportPayables.attr('action', '/report_profits').submit();
    });

    bprint.click(function(e){
        e.preventDefault();
        freportPayables.attr('action', '/report_profits/print').submit();
    });

    bexcel.click(function(e){
        e.preventDefault();
        typePrint.val('excel');
        freportPayables.attr('action', '/report_profits/print').submit();
    });
});