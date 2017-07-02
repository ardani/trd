var sProduct = $('.select-product');
var sPO = $('.select-no-po');
var sOrder = $('.select-no-order');
var sSale = $('.select-no-sale');
var sProductRaw = $('.select-product-raw');
var sProductProduction = $('.select-product-production');
var sCustomer = $('.select-customer');
var sSupplier = $('.select-supplier');
var sAccountCode = $('.select-account-code');

function calculateTotal(el) {
    var cash = $('#cash');
    var sum = 0;
    var charge = 0;
    el.find('tbody tr').each(function () {
        var subtotal = numeral($(this).find('.subtotal').text()).value();
        sum += parseFloat(subtotal);
    });
    $('#total').text(numeral(sum).format('0,0'));
    $('#afterDisc').text(numeral(sum).format('0,0'));
    $('#charge').text('0');
    if (cash.val()) {
        charge = sum - parseFloat(cash.val())
        $('#charge').text(numeral(charge).format('0,0'));
    }
}

function buildDatatables(el, columns) {
    var oTable = el.DataTable({
        responsive: true,
        pageResize: true,
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: {
            url: el.data('url'),
            data: function (d) {
                d.date_until = $('.dateuntil').val();
                d.state_id = $('#state_id').val();
                d.supplier_id = $('[name=supplier_id]').val();
                d.customer_id = $('[name=customer_id]').val();
            }
        },
        columns: columns
    });

    el.on('click', 'a.delete-action', function (e) {
        var el = $(this);
        if (confirm("Are you sure delete this data?")) {
            $.ajax({
                type: "POST",
                url: el.data('url'),
                data: {_token: Laravel.csrfToken},
            }).done(function () {
                oTable.ajax.reload();
            });
        }
    });

    el.on('click', 'a.delete-action-note', function (e) {
        var el = $(this);
        if (confirm("Are you sure delete this data?")) {
            var reason = prompt('please fill reason for delete', '');
            if (reason == null) {
                alert('reason cant empty');
                return;
            }

            $.ajax({
                type: "POST",
                url: el.data('url'),
                data: {_token: Laravel.csrfToken, note: reason},
            }).done(function () {
                oTable.ajax.reload();
            });
        }
    });
}

$(document).ready(function () {
    $.addTemplateFormatter("currency",
        function (value, template) {
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

    $('.radioui').click(function (e) {
        var radio = $(this).find('input[type=radio]');
        radio.prop('checked', !radio.prop('checked'));
    });

    $('.daterange').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

    $('.dateuntil').daterangepicker({
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

  $('.datesingle').daterangepicker({
    showDropdowns: true,
    singleDatePicker: true,
    locale: {
      format: 'DD/MM/YYYY'
    }
  });

    sProduct.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/products/ajaxs/load',
            data: {
              q: '{{{q}}}',
              customer_id: $('select[name=customer_id]').val()
            }
        },
        locale: {
            emptyTitle: '-'
        },
    });

    sProductProduction.selectpicker({liveSearch: true})
        .ajaxSelectPicker({
            ajax: {
                type: 'GET',
                url: '/products/ajaxs/load_production',
            },
            locale: {
                emptyTitle: '-'
            },
        });

    sPO.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/sale_orders/ajaxs/load'
        },
        locale: {
            emptyTitle: '-'
        },
    });

    sOrder.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/orders/ajaxs/load'
        },
        locale: {
            emptyTitle: '-'
        },
    });

    sSale.selectpicker({liveSearch: true})
        .ajaxSelectPicker({
            ajax: {
                type: 'GET',
                url: '/sales/ajaxs/load'
            },
            locale: {
                emptyTitle: '-'
            },
        });

    sPO.on('changed.bs.select', function (e) {
        var customer = $(this).find(":selected").data('customer');
        $('#customer').val(customer);
        $.get($(this).data('url')+'?id='+$(this).val(), function( data ) {
            $('#table-sale-details').find('tbody').loadTemplate("#row-sale", data);
        });
    });

    sOrder.on('changed.bs.select', function (e) {
        var supplier = $(this).find(':selected').data('supplier');
        $('#supplier').val(supplier);
        $.get($(this).data('url')+'?id='+$(this).val(), function( data ) {
            $('#table-order-details').find('tbody').loadTemplate("#row-order", data);
        });
    });

    sProductRaw.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/products/ajaxs/load_raw',
        },
        locale: {
            emptyTitle: '-'
        },
    });

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

    sSupplier.selectpicker({liveSearch: true})
    .ajaxSelectPicker({
        ajax: {
            type: 'GET',
            url: '/suppliers/ajaxs/load',
        },
        locale: {
            emptyTitle: '-'
        },
    });

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

    $('#check-1').click(function (e) {
        var isDisbaled = $('#paid-until-at').prop('disabled');
        $('#paid-until-at').prop('disabled', !isDisbaled);
    });

    $(document).on('click','#set-finish',function (e) {
        if (!confirm('Are you sure change status finish for this production?')) {
            e.preventDefault()
        }
    })
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
        {data: 'unit', searchable: false, orderable: false},
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

    var orders = [
        {data: 'no'},
        {data: 'supplier', searchable: false, orderable: false},
        {data: 'invoice_no'},
        {data: 'payment_info', searchable: false, orderable: false},
        {data: 'cash', searchable: false, orderable: false},
        {data: 'total', searchable: false, orderable: false},
        {data: 'arrive_at', searchable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-orders'), orders);

    var productions = [
        {data: 'sale_order_code', orderable: false},
        {data: 'state',orderable: false,searchable: false},
        {data: 'no'},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-productions'), productions);

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

    var correctionStocks = [
        {data: 'product_code',searchable: false, orderable: false},
        {data: 'product_name',searchable: false, orderable: false},
        {data: 'qty', searchable: false, orderable: false},
        {data: 'units', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false}
    ];
    buildDatatables($('#table-correction-stocks'), correctionStocks);

    var returnSaleOrders = [
        {data: 'no',searchable: false, orderable: false},
        {data: 'sale_order_no',searchable: false, orderable: false},
        {data: 'note', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false}
    ];
    buildDatatables($('#table-return-sale-order'), returnSaleOrders);

    var returnOrders = [
        {data: 'product_code',searchable: false, orderable: false},
        {data: 'order_no',searchable: false, orderable: false},
        {data: 'note', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false}
    ];

    buildDatatables($('#table-return-order'), returnOrders);

    var takeProduct = [
        {data: 'product_code',searchable: false, orderable: false},
        {data: 'product_name',searchable: false, orderable: false},
        {data: 'qty', searchable: false, orderable: false},
        {data: 'units', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false}
    ];

    buildDatatables($('#table-take-product'), takeProduct);

    var paymentOrder = [
        {data: 'order_no', searchable: false, orderable: false},
        {data: 'supplier', searchable: false, orderable: false},
        {data: 'total', searchable: false, orderable: false},
        {data: 'payment', searchable: false, orderable: false},
        {data: 'status', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];

    buildDatatables($('#table-payment-order'), paymentOrder);

    var paymentSale = [
        {data: 'sale_no', searchable: false, orderable: false},
        {data: 'customer', searchable: false, orderable: false},
        {data: 'total', searchable: false, orderable: false},
        {data: 'payment', searchable: false, orderable: false},
        {data: 'status', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];

    buildDatatables($('#table-payment-sale'), paymentSale);

    var paymentDetail = [
        {data: 'account_name', searchable: false, orderable: false},
        {data: 'amount', searchable: false, orderable: false},
        {data: 'note', searchable: false, orderable: false},
        {data: 'giro', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];

    buildDatatables($('#table-payment-detail'), paymentDetail);

    var cashIns = [
        {data: 'no', searchable: false, orderable: false},
        {data: 'account_cash_name', searchable: false, orderable: false},
        {data: 'total', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-cash-ins'), cashIns);

    var cashOuts = [
        {data: 'no', searchable: false, orderable: false},
        {data: 'account_cash_name', searchable: false, orderable: false},
        {data: 'total', searchable: false, orderable: false},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-cash-outs'), cashOuts);

    var requestProduct = [
        {data: 'no'},
        {data: 'note'},
        {data: 'state'},
        {data: 'created_at', searchable: false},
        {data: 'action', searchable: false, orderable: false},
    ];
    buildDatatables($('#table-request-product'), requestProduct);

});