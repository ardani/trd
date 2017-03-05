@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Return <span class="text-danger">*</span></label>
    <input id="no-rs" readonly type="text" name="no" class="form-control" value="{{ $model->no }}">
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No PO <span class="text-danger">*</span></label>
    <input id="no-po" readonly type="text" name="no" class="form-control" value="{{ $model->sale_order->no }}">
    {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Customer <span class="text-danger">*</span></label>
    <input id="customer" type="text" readonly value="{{$model->sale_order->customer->name}}" class="form-control"/>
</fieldset>
<fieldset class="form-group col-md-2 pull-md-right">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           value="{{$model->created_at->format('d/m/Y')}}"
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
        @if($model->sale_order->transactions)
            @foreach($model->sale_order->transactions as $transaction)
                <tr>
                    <td>{{$transaction->product->code}}</td>
                    <td>{{$transaction->product->name}}</td>
                    <td>{{abs($transaction['qty'])}}</td>
                    <td><input data-id="{{$transaction['product_id']}}"
                               type="number"
                               value="1" class="form-control col-md-1 qty-input"/></td>
                    <td>
                        <a class="act-add-return" data-url="{{url('return_sale_orders/actions/add')}}"
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
        @if($model->transactions)
            @foreach($model->transactions as $transaction)
                <tr>
                    <td>{{$transaction->product->code}}</td>
                    <td>{{$transaction->product->name}}</td>
                    <td>{{$transaction['qty']}}</td>
                    <td>
                        <a class="act-return-delete" data-url="{{url('return_sale_orders/actions/delete')}}"
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
            <a class="act-return-delete" data-url="{{url('return_sale_orders/actions/delete')}}"
               data-template-bind='[{"attribute": "data-id", "value": "product_id"}]'
               href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span>
            </a>
        </td>
    </tr>
</script>