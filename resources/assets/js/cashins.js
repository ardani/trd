/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tCashinsDetails = $('#table-cashins-details');
    var debit = $('#debit');
    var note = $('#note');
    var accountCode = $('#account_code_id');

    function resetForm() {
        debit.val('');
        note.val('');
    }

    function calculateTotal(el) {
        var sum = 0;
        el.find('tbody tr').each(function () {
            var subtotal = numeral($(this).find('.debit').text()).value();
            sum += parseFloat(subtotal);
        });
        $('#total').val(numeral(sum).format('0,0'));
    }


    $('#save-btn').click(function (e) {
        if (!accountCode.val()) {
            alert('Account Code masih kosong');
            return false;
        }

        if (!debit.val() ) {
            alert('Debit masih kosong');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                account_code_id: accountCode.val(),
                debit: debit.val(),
                note: note.val(),
                _token: Laravel.csrfToken,
                no: $('#no').val()
            },
            success: function (data) {
                tCashinsDetails.find('tbody').loadTemplate("#row-cash", data);
                resetForm();
                calculateTotal(tCashinsDetails);
            }
        }).fail(function () {
            alert('Add Data Error. Try Again Later');
        })
    });

    tCashinsDetails.on('click', 'a.act-delete', function (e) {
        var id = $(this).data('id');
        if (!confirm('Are you sure delete this data?')) {
            return;
        }
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {no: $('#no').val(), id: id, _token: Laravel.csrfToken},
            success: function (data) {
                tCashinsDetails.find('tbody').loadTemplate("#row-cash", data);
                calculateTotal(tCashinsDetails);
            }
        }).fail(function () {
            alert('delete row failed');
        })
    });

    $('#save-cashins-btn').click(function (e) {
        var url = $(this).data('redirect');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-cashins').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function () {
                alert('Save Cash Ins success');
                window.location.replace(url);
            }
        }).fail(function () {
            alert('Save Data Error. Try Again Later');
        })
    });
});