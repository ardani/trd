@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No PO <span class="text-danger">*</span></label>
    <input id="no-po" type="text" name="no" class="form-control" readonly
           value="{{ $auto_number_sales }}">
    {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Customer <span class="text-danger">*</span></label>
    <select name="customer_id" class="form-control select-customer" data-live-search="true">
        <option value="1" data-customer_type_id="1">umum</option>
    </select>
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Credit</label>
    <div class="input-group">
        <div class="input-group-addon">
            <input type="checkbox" name="payment_method_id" value="2" id="check-1">
        </div>
        <input disabled type="text" id="paid-until-at" name="paid_until_at" class="form-control daterange"
               placeholder="credit date">
    </div>
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Shop <span class="text-danger">*</span></label>
    <select name="shop_id" class="form-control">
        @foreach($shops as $shop)
            <option value="{{$shop->id}}">{{$shop->name}}</option>
        @endforeach
    </select>
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
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Price</label>
    <input type="number" id="selling_price" class="form-control" name="selling_price"/>
</fieldset>
<fieldset class="form-group col-md-4" id="units">
</fieldset>
<fieldset class="form-group col-md-10">
    <label class="form-control-label">Desc</label>
    <input type="text" id="desc" placeholder="desc" class="form-control" name="desc"/>
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Qty <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="number" id="qty" placeholder="Qty" class="form-control" value="1">
        <div class="input-group-btn">
            <button type="button" id="save-btn" data-url="{{url('sale_orders/actions/addTemp')}}"
                    class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span></button>
        </div>
    </div>
</fieldset>
<div class="clearfix"></div>
<div class="col-md-12">
    <table id="table-sale-details" class="display table table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th width="10%">Code</th>
            <th>Product Name</th>
            <th width="15%">Price</th>
            <th width="10%">Disc</th>
            <th width="10%">Units</th>
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
                    <td>{{number_format($transaction['selling_price'])}}</td>
                    <td>{{number_format($transaction['disc'])}}</td>
                    <td>{{ $transaction['units'] }}</td>
                    <td>{{$transaction['qty']}}</td>
                    <td>{{number_format($transaction['subtotal'])}}</td>
                    <td>
                        <a class="act-delete" data-url="{{url('sale_orders/actions/deleteTemp')}}"
                           data-id="{{$transaction['id']}}" data-key="{{$transaction['id']}}" href="javascript:void(0)"><span
                                    class="glyphicon glyphicon-remove"></span></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="empty-row">
                <td colspan="8" class="text-center">empty data</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<div class="col-md-9" style="margin-top: 10px">
    <div class="form-group">
        <textarea name="note" class="form-control" cols="30" rows="4" placeholder="note"></textarea>
    </div>
</div>
<div class="col-md-3" style="margin-top: 10px">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">Disc Rp</div>
            <input type="number" name="disc" id="disc" class="form-control" placeholder="0">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">Pay &nbsp;Rp</div>
            <input type="number" id="cash" name="cash" placeholder="0" class="form-control">
        </div>
    </div>
</div>
<script type="text/html" id="row-sale">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td class="text-right" data-content="selling_price" data-format="currency"></td>
        <td class="text-right" data-content="disc" data-format="currency"></td>
        <td data-content="units"></td>
        <td data-content="qty"></td>
        <td class="text-right subtotal" data-content="subtotal" data-format="currency"></td>
        <td>
            <a class="act-delete" data-url="{{url('sale_orders/actions/deleteTemp')}}"
               data-template-bind='[{"attribute": "data-id", "value": "id"}]' href="javascript:void(0)"><span
                        class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script>