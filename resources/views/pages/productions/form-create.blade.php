@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Productions <span class="text-danger">*</span></label>
    <input id="no-order" type="text" name="no" class="form-control" readonly
           value="{{ $auto_number_productions }}">
        {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">No PO</label>
    <input type="text" class="form-control" value="">
</fieldset>

<fieldset class="form-group col-md-2 pull-md-right">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           data-validation="[NOTEMPTY]">
</fieldset>
<div class="clearfix"></div>
<hr class="hr-form"/>
<fieldset class="form-group col-md-3">
    <select id="product_id" class="form-control select-product" data-live-search="true"></select>
</fieldset>
<fieldset class="form-group col-md-3" id="units">

</fieldset>
<fieldset class="form-group col-md-2">
    <div class="input-group">
        <input type="number" id="qty" placeholder="Qty" class="form-control" value="">
        <div class="input-group-btn">
            <button type="button" id="save-btn" data-url="{{url('productions/actions/addTemp')}}" class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span></button>
        </div>
    </div>
</fieldset>
<div class="clearfix"></div>
<div class="col-md-12">
<table id="table-productions-details" class="display table table-bordered" cellspacing="0" width="100%">
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
                    <td><input data-id="{{$transaction['product_id']}}"
                               type="number" data-url="{{url('productions/actions/addTemp')}}"
                               value="{{$transaction['qty']}}" class="form-control col-md-1 qty-input"/></td>
                    <td>
                        <a class="act-delete" data-url="{{url('productions/actions/deleteTemp')}}"
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
<script type="text/html" id="row-order">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td class="text-right" data-content="purchase_price" data-format="currency"></td>
        <td><input type="number" data-url="{{url('productions/actions/addTemp')}}" data-template-bind='[
            {"attribute": "data-id", "value": "product_id"}
        ]' data-value="qty" class="form-control col-md-1 qty-input"/></td>
        <td>
            <a class="act-delete" data-url="{{url('productions/actions/deleteTemp')}}" data-template-bind='[{"attribute": "data-id", "value": "product_id"}]' href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script> 