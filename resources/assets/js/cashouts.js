/**
 * Created by ardani on 2/10/17.
 */
$(document).ready(function () {
    var tCashoutsDetails = $('#table-cashouts-details');
    var credit = $('#credit');
    var note = $('#note');
    var mutation = $('#mutation');
    var accountCode = $('#account_code_id');
    var date = $('input[name=created_at]');

    function resetForm() {
        credit.val('');
        note.val('');
    }

    function calculateTotal(el) {
        var sum = 0;
        el.find('tbody tr').each(function () {
            var subtotal = numeral($(this).find('.credit').text()).value();
            sum += parseFloat(subtotal);
        });
        $('#total').val(numeral(sum).format('0,0'));
    }


    $('#save-btn').click(function (e) {
        if (!accountCode.val()) {
            alert('Account Code masih kosong');
            return false;
        }

        if (!credit.val() ) {
            alert('Debit masih kosong');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                account_code_id: accountCode.val(),
                credit: credit.val(),
                note: note.val(),
                mutation: mutation.val(),
                created_at: date.val(),
                _token: Laravel.csrfToken,
                no: $('#no').val()
            },
            success: function (data) {
                tCashoutsDetails.find('tbody').loadTemplate("#row-cash", data);
                resetForm();
                calculateTotal(tCashoutsDetails);
            }
        }).fail(function () {
            alert('Add Data Error. Try Again Later');
        })
    });

    tCashoutsDetails.on('click', 'a.act-delete', function (e) {
        var id = $(this).data('id');
        if (!confirm('Are you sure delete this data?')) {
            return;
        }
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {no: $('#no').val(), id: id, _token: Laravel.csrfToken},
            success: function (data) {
                tCashoutsDetails.find('tbody').loadTemplate("#row-cash", data);
                calculateTotal(tCashoutsDetails);
            }
        }).fail(function () {
            alert('delete row failed');
        })
    });

    $('#save-cashouts-btn').click(function (e) {
        var url = $(this).data('redirect');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-cashouts').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function () {
                alert('Save data success');
                window.location.replace(url);
            }
        }).fail(function () {
            alert('Save Data Error. Try Again Later');
        })
    });
});