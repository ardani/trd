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

$(document).ready(function () {
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

    $('.select-product').selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/products/ajaxs/load',
        },
        locale: {
            emptyTitle: '-'
        },
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

});