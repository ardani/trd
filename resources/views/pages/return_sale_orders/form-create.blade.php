@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Return <span class="text-danger">*</span></label>
    <input id="no-rs" type="text" name="no" readonly class="form-control" value="{{auto_number_return_sales()}}">
    {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No PO <span class="text-danger">*</span></label>
    <select name="no_sale" class="form-control select-no-po" data-url="{{url('sale_orders/ajaxs/detail')}}"></select>
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Customer <span class="text-danger">*</span></label>
    <input id="customer" type="text" readonly class="form-control"/>
</fieldset>
<fieldset class="form-group col-md-2 pull-md-right">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           data-validation="[NOTEMPTY]">
</fieldset>
<div class="clearfix"></div>
<hr class="hr-form"/>
<div class="clearfix"></div>
<div class="col-md-12">
    <p>Sale Order Detail</p>
<table id="table-sale-details" class="display table table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="10%">Code</th>
        <th>Product Name</th>
        <th width="10%">Qty</th>
        <th width="10%">R.Qty</th>
        <th width="5%">Action</th>
    </tr>
    </thead>
    <tbody>
        @if($transactions)
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{$transaction['code']}}</td>
                    <td>{{$transaction['name']}}</td>
                    <td>{{$transaction['qty']}}</td>
                    <td><input data-id="{{$transaction['product_id']}}"
                               type="number" data-url="{{url('return_sale_orders/actions/addTemp')}}"
                               value="1" class="form-control col-md-1 qty-input"/></td>
                    <td>
                        <a class="act-add-return" data-url="{{url('return_sale_orders/actions/addTemp')}}"
                           data-id="{{$transaction['product_id']}}" href="javascript:void(0)"><span class="glyphicon glyphicon-plus"></span></a>
                    </td>
                </tr>
            @endforeach
        @else
        <tr class="empty-row">
            <td colspan="7" class="text-center">empty data</td>
        </tr>
        @endif
    </tbody>
</table>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
    <p>Return Sale Order Detail</p>
    <table id="table-return-sale-details" class="display table table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th width="10%">Code</th>
            <th>Product Name</th>
            <th width="10%">Qty</th>
            <th width="5%">Action</th>
        </tr>
        </thead>
        <tbody>
        @if($transactions)
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{$transaction['code']}}</td>
                    <td>{{$transaction['name']}}</td>
                    <td>{{$transaction['qty']}}</td>
                    <td>
                        <a class="act-return-delete" data-url="{{url('return_sale_orders/actions/deleteTemp')}}"
                           data-id="{{$transaction['product_id']}}" href="javascript:void(0)">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="empty-row">
                <td colspan="7" class="text-center">empty data</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<script type="text/html" id="row-return-sale">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td data-content="qty"></td>
        <td>
            <a class="act-return-delete" data-url="{{url('return_sale_orders/actions/deleteTemp')}}"
               data-template-bind='[{"attribute": "data-id", "value": "product_id"}]'
               href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span>
            </a>
        </td>
    </tr>
</script>
<script type="text/html" id="row-sale">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td data-content="qty"></td>
        <td><input type="number" value="1" class="form-control qty-input"/></td>
        <td>
            <a class="act-add-return" data-url="{{url('return_sale_orders/actions/addTemp')}}"
               data-template-bind='[{"attribute": "data-id", "value": "product_id"},{"attribute": "data-qty", "value": "qty"}]'
               href="javascript:void(0)"><span class="glyphicon glyphicon-plus"></span>
            </a>
        </td>
    </tr>
</script>