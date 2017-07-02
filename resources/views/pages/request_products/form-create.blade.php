@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No <span class="text-danger">*</span></label>
    <input id="no-po" type="text" name="no" class="form-control" readonly value="{{ $auto_number }}">
    {{ csrf_field() }}
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
    <select id="product_id" class="form-control select-product-raw" data-live-search="true"></select>
</fieldset>
<fieldset class="form-group col-md-4" id="units">
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Qty <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="number" id="qty" placeholder="Qty" class="form-control" value="1">
        <div class="input-group-btn">
            <button type="button" id="save-btn" data-url="{{url('request_products/actions/addTemp')}}"
                    class="btn btn-info"><span class="glyphicon glyphicon-floppy-saved"></span></button>
        </div>
    </div>
</fieldset>
<div class="clearfix"></div>
<div class="col-md-12">
    <table id="table-request-details" class="display table table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th width="15%">Code</th>
            <th>Product Name</th>
            <th width="15%">Units</th>
            <th width="15%">Qty</th>
            <th width="5%">Action</th>
        </tr>
        </thead>
        <tbody>
        @if($transactions)
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{$transaction['code']}}</td>
                    <td>{{$transaction['name']}}</td>
                    <td>{{ $transaction['units']}}</td>
                    <td>{{ $transaction['qty']}}</td>
                    <td><a class="act-delete" data-url="{{url('request_products/actions/deleteTemp')}}"
                           data-id="{{$transaction['product_id']}}" href="javascript:void(0)">
                            <span class="glyphicon glyphicon-remove"></span></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="empty-row">
                <td colspan="5" class="text-center">empty data</td>
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
<script type="text/html" id="row-sale">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td data-content="units"></td>
        <td data-content="qty"></td>
        <td>
            <a class="act-delete" data-url="{{url('request_products/actions/deleteTemp')}}"
               data-template-bind='[{"attribute": "data-id", "value": "product_id"}]' href="javascript:void(0)"><span
                        class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script>