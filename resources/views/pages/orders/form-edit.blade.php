@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Order <span class="text-danger">*</span></label>
    <input id="no-order" type="text" name="no" class="form-control" readonly
           value="{{ $model->no }}">
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
            <input type="checkbox" {{$model->payment_method_id == 2 ? 'checked' : ''}}  name="payment_method_id" value="2" id="check-1">
        </div>
        <input {{$model->payment_method_id == 2 ? '' : 'disabled'}}  type="text" id="paid-until-at" name="paid_until_at" value="{{$model->paid_until_at ? $model->paid_until_at->format('d/m/Y') : date('d/m/Y') }}" class="form-control daterange" placeholder="credit date">
    </div>
</fieldset>
<fieldset class="form-group col-md-2 pull-md-right">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           value="{{$model->created_at->format('d/m/Y')}}"
           data-validation="[NOTEMPTY]">
</fieldset>
<div class="clearfix"></div>
<hr class="hr-form"/>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Product <span class="text-danger">*</span></label>
    <select id="product_id" class="form-control select-product-raw" data-live-search="true"></select>
</fieldset>
<fieldset class="form-group col-md-1">
    <label class="form-control-label">Purchase Price <span class="text-danger">*</span></label>
    <input type="number" id="purchase_price" placeholder="Purchase Price" class="form-control" value="">
</fieldset>
<fieldset class="form-group col-md-1">
    <label class="form-control-label">Selling Price <span class="text-danger">*</span></label>
    <input type="number" id="selling_price" placeholder="Selling Price" class="form-control" value="">
</fieldset>
<fieldset class="form-group col-md-4" id="units">
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Qty <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="number" id="qty" placeholder="Qty" class="form-control" value="">
        <div class="input-group-btn">
            <button type="button" id="save-btn" data-url="{{url('orders/actions/add')}}" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span></button>
        </div>
    </div>
</fieldset>
<div class="clearfix"></div>
<div class="col-md-12">
<table id="table-orders-details" class="display table table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="10%">Code</th>
        <th>Product Name</th>
        <th width="15%">Purchase Price</th>
        <th width="15%">Selling Price</th>
        <th width="10%">Unit</th>
        <th width="10%">Qty</th>
        <th width="15%">Sub Total</th>
        <th width="5%">Action</th>
    </tr>
    </thead>
    <tbody>
        <tr class="empty-row">
            @foreach($model->transactions as $transaction)
            <tr>
                <td>{{$transaction->product->code}}</td>
                <td>{{$transaction->product->name}}</td>
                <td class="text-right">{{number_format($transaction->purchase_price)}}</td>
                <td>{{$transaction['units']}}</td>
                <td>
                    <input data-id="{{$transaction->product_id}}"
                           data-purchase_price="{{$transaction->purchase_price}}"
                           data-selling_price="{{$transaction->selling_price}}"
                           data-attribute="{{$transaction->attribute}}"
                           type="number" data-url="{{url('orders/actions/add')}}"
                           value="{{$transaction->qty}}" class="form-control col-md-1 qty-input"/>
                </td>
                <td class="text-right subtotal" data-content="subtotal">{{number_format($transaction->qty * $transaction->purchase_price)}}</td>
                <td>
                    <a class="act-delete" data-url="{{url('orders/actions/delete')}}"
                       data-id="{{$transaction->product_id}}" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
            </tr>
            @endforeach
        </tr>
    </tbody>
</table>
</div>
<div class="col-md-3 pull-md-right" style="margin-top: 10px">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">Rp</div>
            <input type="number" id="cash" value="{{$model->cash}}" name="cash" placeholder="0" class="form-control">
            <div class="input-group-btn">
                <button id="pay-btn" type="button" class="btn btn-info">DP</button>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="row-order">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td class="text-right" data-content="purchase_price" data-format="currency"></td>
        <td class="text-right" data-content="selling_price" data-format="currency"></td>
        <td data-content="attribute"></td>
        <td><input type="number" data-url="{{url('orders/actions/add')}}" data-template-bind='[
            {"attribute": "data-id", "value": "product_id"},
            {"attribute": "data-attribute", "value": "attribute"},
            {"attribute": "data-purchase_price", "value": "purchase_price"},
            {"attribute": "data-selling_price", "value": "selling_price"}
        ]' data-value="qty" class="form-control col-md-1 qty-input"/></td>
        <td class="text-right subtotal" data-format="currency" data-content="subtotal"></td>
        <td>
            <a class="act-delete" data-url="{{url('orders/actions/delete')}}" data-template-bind='[{"attribute": "data-id", "value": "product_id"}]' href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script> 