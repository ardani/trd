$(document).ready(function () {
    var freportPayables = $('#freport-payables');
    var bview = $('#bview');
    var bprint = $('#bprint');
    var bexcel = $('#bexcel');
    var typePrint = $('#type-print');

    bview.click(function(e){
        e.preventDefault();
        freportPayables.attr('action', '/report_payables').submit();
    });

    bprint.click(function(e){
        e.preventDefault();
        freportPayables.attr('action', '/report_payables/print').submit();
    });

    bexcel.click(function(e){
        e.preventDefault();
        typePrint.val('excel');
        freportPayables.attr('action', '/report_payables/print').submit();
    });
});