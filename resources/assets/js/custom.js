function deleteAction(oTable) {
    $('table').on('click', 'a.delete-action', function (e) {
        e.preventDefault();
        var el = $(this);
        swal({
            title: "Confirmation",
            text: "Are you sure delete this data?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            confirmButtonClass: "btn-danger"
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: el.data('url'),
                    data: {_token: Laravel.csrfToken}
                })
                .done(function () {
                    oTable.ajax.reload();
                });
            }
        });
    });
}

function buildDatatables(el, columns) {
    var oTable = el.DataTable({
        responsive: true,
        pageResize: true,
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: el.data('url'),
        columns: columns
    });
    deleteAction(oTable);
}

function calculateTotal(el) {
    var sum = 0;
    el.find('tbody tr').each(function () {
        var subtotal = numeral($(this).find('.subtotal').text()).value();
        sum += parseInt(subtotal);
    });
    $('#total').text(numeral(sum).format('0,0'));
}

function clearPurchaseOrder() {
    $('#total').text(0);
    $('#disc').val(0);
    $('#cash').val(0);
}

$(document).ready(function () {
    $.addTemplateFormatter("currency",
        function(value, template) {
            return numeral(value).format('0,0');
        });

    $('#formValid').validate({
        submit: {
            settings: {
                inputContainer: '.form-group'
            }
        }
    });

    $('#unit').change(function (e) {
        var val = $(this).val();
        var url = $(this).data('url');
        $.get(url + '/' + val, function (data) {
            $('#componentUnit').html(data);
        });
    })

    $('.checkui').click(function (e) {
        var checkbox = $(this).find('input[type=checkbox]');
        checkbox.prop('checked', !checkbox.prop('checked'));
    });

    $('.daterange').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    var sProduct = $('.select-product');
    sProduct.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/products/ajaxs/load',
        },
        locale: {
            emptyTitle: '-'
        },
    });

    var sCustomer = $('.select-customer');
    sCustomer.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/customers/ajaxs/load',
        },
        locale: {
            emptyTitle: '-'
        },
    });

    var sAccountCode = $('.select-account-code');
    sAccountCode.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/account_codes/ajaxs/load',
        },
        locale: {
            emptyTitle: '-'
        },
    });

    $('#check-1').click(function(e){
        var isDisbaled = $('#paid-until-at').prop('disabled');
        $('#paid-until-at').prop('disabled',!isDisbaled);
    });

    var tPurchaseDetails = $('#table-purchase-details');

    $('#save-btn').click(function (e) {
        var custTypeId = sCustomer.find('option:selected').data('customer_type_id');
        var qty = $('#qty');
        if (!custTypeId) {
            alert('customer belum dipilih');
            return false;
        }
        if (!sProduct.val()) {
            alert('product belum dipilih');
            return false;
        }
        if (!qty.val()) {
            alert('Qty masih kosong');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {
                product_id: sProduct.val(),
                qty: qty.val(),
                customer_type_id: custTypeId,
                _token: Laravel.csrfToken,
                no:$('#no-po').val()
            },
            success: function (data) {
                tPurchaseDetails.find('tbody').loadTemplate("#row-purchase", data);
                sProduct.selectpicker('refresh');
                qty.val('');
                calculateTotal(tPurchaseDetails);
            }
        }).fail(function() {
            alert('Add Purchase Product Error. Try Again Later');
        })
    });

    $('#count-btn').click(function (e) {
       var total = parseInt(numeral($('#total').text()).value());
       var disc = parseInt($('#disc').val());
       $('#total').text(numeral(total-disc).format('0,0'));
    });

    $('#calculate-btn').click(function (e){
        calculateTotal(tPurchaseDetails);
    });

    $('#pay-btn').click(function (e) {
        var total = parseInt(numeral($('#total').text()).value());
        var cash = parseInt($('#cash').val());
        $('#charge').text(numeral(cash-total).format('0,0'));
    });

    $('table').on('keypress','.qty-input', function (e) {
        if(e.which == 13) {
            var custTypeId = sCustomer.find('option:selected').data('customer_type_id');
            var self = $(this);
            $.ajax({
                type: 'POST',
                url: self.data('url'),
                data: {
                    product_id: self.data('id'),
                    qty: self.val(),
                    customer_type_id: custTypeId,
                    _token: Laravel.csrfToken,
                    is_edit : 1,
                    no:$('#no-po').val()
                },
                success: function (data) {
                    tPurchaseDetails.find('tbody').loadTemplate("#row-purchase", data);
                    calculateTotal(tPurchaseDetails);
                }
            }).fail(function() {
                alert('Update Purchase Product Error. Try Again Later');
            })
        }
    });

    $('table').on('click', 'a.act-delete', function (e) {
        var product_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: {no:$('#no-po').val(),product_id: product_id,_token:Laravel.csrfToken},
            success: function (data) {
                tPurchaseDetails.find('tbody').loadTemplate("#row-purchase", data);
                calculateTotal(tPurchaseDetails);
            }
        }).fail(function() {
            alert('delete row failed');
        })
    });

    $('#save-pruchase-btn').click(function (e) {
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $('#form-po').serialize(),
            headers: {
                'X-CSRF-Token': Laravel.csrfToken
            },
            success: function (data) {
                alert('Save PO success');
            }
        }).fail(function() {
            alert('Save PO Error. Try Again Later');
        })
    });
    // menus
    var menus = [
        {data: 'name'},
        {data: 'path', orderable: false, searchable: false},
        {data: 'icon', searchable: false, orderable: false},
        {data: 'class', searchable: false, orderable: false},
        {data: 'description', searchable: false, orderable: false},
        {data: 'order', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-menus'), menus);
    // roles
    var roles = [
        {data: 'name'},
        {data: 'display_name', orderable: false, searchable: false},
        {data: 'description', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-roles'), roles);

    // permissions
    var permissions = [
        {data: 'name'},
        {data: 'display_name', orderable: false, searchable: false},
        {data: 'description', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-permissions'), permissions);

    // users
    var users = [
        {data: 'username'},
        {data: 'email'},
        {data: 'role', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-users'), users);

    // customer types
    var customerTypes = [
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-customer-types'), customerTypes);

    // employee types
    var employeeTypes = [
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-employee-types'), employeeTypes);

    var categories = [
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-categories'), categories);

    var units = [
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-units'), units);

    var settings = [
        {data: 'key'},
        {data: 'name'},
        {data: 'value'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-settings'), settings);

    var paymentMethods = [
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-payment-methods'), paymentMethods);

    var accountCodes = [
        {data: 'id'},
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-account-codes'), accountCodes);

    var customers = [
        {data: 'name'},
        {data: 'phone'},
        {data: 'address'},
        {data: 'type', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-customers'), customers);

    var suppliers = [
        {data: 'name'},
        {data: 'phone'},
        {data: 'address'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-suppliers'), suppliers);

    var componentUnits = [
        {data: 'code'},
        {data: 'name'},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-component-units'), componentUnits);

    var products = [
        {data: 'code'},
        {data: 'name'},
        {data: 'category', searchable: false, orderable: false},
        {data: 'selling_price', searchable: false, orderable: false},
        {data: 'min_stock', searchable: false, orderable: false},
        {data: 'stock', searchable: false, orderable: false},
        {data: 'supplier', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-products'), products);

    var discounts = [
        {data: 'product', searchable: false, orderable: false},
        {data: 'amount', searchable: false, orderable: false},
        {data: 'expired_at', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-discounts'), discounts);

    var productPrices = [
        {data: 'customer_type', searchable: false, orderable: false},
        {data: 'selling_price', searchable: false, orderable: false},
        {data: 'purchase_price', searchable: false, orderable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-product-prices'), productPrices);

    var po = [
        {data: 'no'},
        {data: 'customer', searchable: false, orderable: false},
        {data: 'state', searchable: false, orderable: false},
        {data: 'payment_info', searchable: false, orderable: false},
        {data: 'cash', searchable: false, orderable: false},
        {data: 'disc', searchable: false, orderable: false},
        {data: 'total', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-po'), po);

    var cashflows = [
        {data: 'account_code_id'},
        {data: 'account_name', searchable: false, orderable: false},
        {data: 'debit', searchable: false, orderable: false},
        {data: 'credit', searchable: false, orderable: false},
        {data: 'note', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-cash-flows'), cashflows);

});