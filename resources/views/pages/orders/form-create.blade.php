@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Order <span class="text-danger">*</span></label>
    <input id="no-order" type="text" name="no" class="form-control" readonly
           value="{{ $auto_number_sales }}">
        {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Invoice No</label>
    <input type="text" class="form-control" name="invoice_no">
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Supplier <span class="text-danger">*</span></label>
    <select name="supplier_id" class="form-control select-supplier" data-live-search="true">
    </select>
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Credit</label>
    <div class="input-group">
        <div class="input-group-addon">
            <input type="checkbox" name="payment_method_id" value="2" id="check-1">
        </div>
        <input disabled type="text" id="paid-until-at" name="paid_until_at" class="form-control daterange" placeholder="credit date">
    </div>
</fieldset>
<fieldset class="form-group col-md-2 pull-md-right">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           data-validation="[NOTEMPTY]">
</fieldset>
<div class="clearfix"></div>
<hr class="hr-form"/>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Product <span class="text-danger">*</span></label>
    <select id="product_id" class="form-control select-product" data-live-search="true"></select>
</fieldset>
<fieldset class="form-group col-md-2">
        <label class="form-control-label">Purchase Price <span class="text-danger">*</span></label>
        <input type="number" id="purchase_price" placeholder="Purchase Price" class="form-control" value="">
</fieldset>
<fieldset class="form-group col-md-3">
        <label class="form-control-label">Unit (LxWxH) cm <span class="text-danger">*</span></label>
        <input type="number" id="length" style="width: 30%;float: left;" placeholder="L" class="form-control" value="1">
        <input type="number" id="width" style="width: 30%;float: left;margin:0 5px;" placeholder="W" class="form-control" value="1">
        <input type="number" id="height" style="width: 30%;" placeholder="H" class="form-control" value="1">
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Qty <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="number" id="qty" placeholder="Qty" class="form-control" value="">
        <div class="input-group-btn">
            <button type="button" id="save-btn" data-url="{{url('orders/actions/addTemp')}}" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span></button>
        </div>
    </div>
</fieldset>
{{--<fieldset class="form-group col-md-1 pull-md-right">
    <button id="calculate-btn" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span></button>
</fieldset>--}}

<div class="clearfix"></div>
<div class="col-md-12">
<table id="table-orders-details" class="display table table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="10%">Code</th>
        <th>Product Name</th>
        <th width="15%">Price</th>
        <th width="10%">Unit</th>
        <th width="10%">Qty</th>
        <th width="15%">Sub Total</th>
        <th width="5%">Action</th>
    </tr>
    </thead>
    <tbody>
        @if($transactions)
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{$transaction['code']}}</td>
                    <td>{{$transaction['name']}}</td>
                    <td>{{number_format($transaction['purchase_price'])}}</td>
                    <td>{{$transaction['attribute']}}</td>
                    <td><input data-id="{{$transaction['product_id']}}"
                               data-purchase_price="{{$transaction['purchase_price']}}"
                               data-attribute="{{$transaction['attribute']}}"
                               type="number" data-url="{{url('orders/actions/addTemp')}}"
                               value="{{$transaction['qty']}}" class="form-control col-md-1 qty-input"/></td>
                    <td>{{number_format($transaction['subtotal'])}}</td>
                    <td>
                        <a class="act-delete" data-url="{{url('orders/actions/deleteTemp')}}"
                           data-id="{{$transaction['product_id']}}" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
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
<div class="col-md-3 pull-md-right" style="margin-top: 10px">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">Rp</div>
            <input type="number" id="cash" name="cash" placeholder="0" class="form-control">
            <div class="input-group-btn">
                <button id="pay-btn" type="button" class="btn btn-info">pay</button>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="row-order">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td class="text-right" data-content="purchase_price" data-format="currency"></td>
        <td data-content="attribute"></td>
        <td><input type="number" data-url="{{url('orders/actions/addTemp')}}" data-template-bind='[
            {"attribute": "data-id", "value": "product_id"},
            {"attribute": "data-attribute", "value": "attribute"},
            {"attribute": "data-purchase_price", "value": "purchase_price"}
        ]' data-value="qty" class="form-control col-md-1 qty-input"/></td>
        <td class="text-right subtotal" data-content="subtotal" data-format="currency"></td>
        <td>
            <a class="act-delete" data-url="{{url('orders/actions/deleteTemp')}}" data-template-bind='[{"attribute": "data-id", "value": "product_id"}]' href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script> 