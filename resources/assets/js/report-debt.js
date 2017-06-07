$(document).ready(function () {
    var freportPayables = $('#freport-debts');
    var bview = $('#bview');
    var bprint = $('#bprint');
    var bexcel = $('#bexcel');
    var typePrint = $('#type-print');

    bview.click(function(e){
        e.preventDefault();
        freportPayables.attr('action', '/report_debts').submit();
    });

    bprint.click(function(e){
        e.preventDefault();
        freportPayables.attr('action', '/report_debts/print').submit();
    });

    bexcel.click(function(e){
        e.preventDefault();
        typePrint.val('excel');
        freportPayables.attr('action', '/report_debts/print').submit();
    });
});