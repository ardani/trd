@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Production <span class="text-danger">*</span></label>
    <input id="no-production" type="text" name="no" class="form-control" readonly
           value="{{ $model->no }}">
    {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No PO</label>
    <input type="text" readonly class="form-control" value="{{$model->sale_order->no}}">
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Status</label>
    <select name="state_id" class="form-control">
        @foreach($states as $state)
            @if($model->sale_order->state_id == $state->id)
                <option selected value="{{$state->id}}">{{$state->name}}</option>
            @else
                <option value="{{$state->id}}">{{$state->name}}</option>
            @endif
        @endforeach
    </select>
</fieldset>
<fieldset class="form-group col-md-2 pull-md-right">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           value="{{$model->created_at->format('d/m/Y')}}"
           data-validation="[NOTEMPTY]">
</fieldset>
<div class="clearfix"></div>
<hr class="hr-form"/>
<div class="col-md-12">
    <table id="listItemOrder" class="display table table-bordered" cellspacing="0" width="100%"
           style="margin-bottom: 10px">
        <thead>
        <tr>
            <th width="10%">Code</th>
            <th>Product Name</th>
            <th width="10%">Qty</th>
            <th width="10%">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($model->sale_order->transactions as $transaction)
            <tr>
                <td>{{$transaction->product->code}}</td>
                <td>{{$transaction->product->name .' - '.$transaction->desc}}</td>
                <td>{{abs($transaction->qty)}}</td>
                <td>
                    <button type="button" data-name="{{$transaction->product->name}}"
                            data-id="{{$transaction->product_id}}"
                            data-url="{{url('productions/actions/detail')}}"
                            class="btn btn-sm btn-success setActive">choose
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
    <h4>Product Selected : <span id="product-selected">-</span></h4>
    <input type="hidden" name="product_selected"/>
</div>
<hr class="hr-form"/>
<div class="form-wrapper" style="display: none">
    <fieldset class="form-group col-md-3">
        <label class="form-control-label">Product <span class="text-danger">*</span></label>
        <select id="product_id" class="form-control select-product" data-live-search="true"></select>
    </fieldset>
    <fieldset class="form-group col-md-4" id="units">
    </fieldset>
    <fieldset class="form-group col-md-2">
        <label class="form-control-label">Qty <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" id="qty" placeholder="Qty" class="form-control" value="">
            <div class="input-group-btn">
                <button type="button" id="save-btn" data-url="{{url('productions/actions/add')}}"
                        class="btn btn-info"><span
                            class="glyphicon glyphicon-floppy-saved"></span></button>
            </div>
        </div>
    </fieldset>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <table id="table-productions-details" class="display table table-bordered" cellspacing="0" width="100%"
               style="margin-bottom: 10px">
            <thead>
            <tr>
                <th width="10%">Code</th>
                <th>Product Name</th>
                <th width="10%">Units</th>
                <th width="10%">Qty</th>
                <th width="5%">Action</th>
            </tr>
            </thead>
            <tbody>
            @if($model->transactions->isEmpty())
                <tr class="empty-row">
                    <td colspan="4" class="text-center">empty data</td>
                </tr>
            @else
                @foreach($model->transactions as $transaction)
                    <tr>
                        <td>{{$transaction->product->code}}</td>
                        <td>{{$transaction->product->name}}</td>
                        <td>{{$transaction->units}}</td>
                        <td>
                            <input data-id="{{$transaction->product_id}}"
                                   type="number" data-url="{{url('productions/actions/add')}}"
                                   value="{{abs($transaction->qty)}}" class="form-control col-md-1 qty-input"/>
                        </td>
                        <td>
                            <a class="act-delete"
                               data-url="{{url('productions/actions/delete')}}"
                               data-id="{{$transaction->product_id}}"
                               href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <fieldset class="form-group">
            <label class="form-control-label">Note</label>
            <p class="text-danger" style="font-weight: bold">{{$model->sale_order->note}}</p>
        </fieldset>
    </div>
</div>
<script type="text/html" id="row-production">
    <tr>
        <td data-content="code"></td>
        <td data-content="name"></td>
        <td data-content="units"></td>
        <td>
            <input type="number" data-url="{{url('productions/actions/add')}}"
                   data-template-bind='[
                      {"attribute": "data-id", "value": "product_id"}
                    ]'
                   data-value="qty" class="form-control col-md-1 qty-input"/>
        </td>
        <td>
            <a class="act-delete" data-url="{{url('productions/actions/delete')}}"
               data-template-bind='[{"attribute": "data-id", "value": "product_id"}]' href="javascript:void(0)"><span
                        class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script> 