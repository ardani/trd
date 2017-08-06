@include('includes.alert')
<fieldset class="form-group col-md-3">
    <label class="form-control-label">No Cash Out <span class="text-danger">*</span></label>
    <input id="no" type="text" name="no" class="form-control" readonly
           value="{{ $model->no }}">
        {{ csrf_field() }}
</fieldset>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Date <span class="text-danger">*</span></label>
    <input type="text" class="form-control daterange" name="created_at"
           data-validation="[NOTEMPTY]" value="{{$model->created_at->format('d/m/Y')}}">
</fieldset>
<fieldset class="form-group col-md-3">
    <label class="form-control-label">Account <span class="text-danger">*</span></label>
    <select id="account_code_id" name="account_code_id" class="form-control select-account-code" data-live-search="true"></select>
</fieldset>
<div class="clearfix"></div>
<hr class="hr-form"/>
<fieldset class="form-group col-md-2">
    <label class="form-control-label">Mutation </label>
    <select name="mutation" id="mutation" class="form-control">
        <option value="0">No</option>
        <option value="1">Yes</option>
    </select>
</fieldset>
<fieldset class="form-group col-md-2">
        <label class="form-control-label">Credit <span class="text-danger">*</span></label>
        <input type="number" id="credit" required placeholder="credit" class="form-control" value="">
</fieldset>
<fieldset class="form-group col-md-5">
    <label class="form-control-label">Note</label>
    <input type="text" id="note" placeholder="note" class="form-control" value="">
</fieldset>
<fieldset class="form-group col-md-1">
    <button style="margin-top: 25px" type="button"
            id="save-btn"
            data-url="{{url('cash_outs/actions/add')}}"
            class="btn btn-success">save</button>
</fieldset>
<div class="clearfix"></div>
<div class="col-md-12">
<table id="table-cashouts-details" class="display table table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="10%">No</th>
        <th width="10%">Account</th>
        <th width="15%">Credit</th>
        <th width="15%">Mutation</th>
        <th width="15%">Note</th>
        <th width="5%">Action</th>
    </tr>
    </thead>
    <tbody>
        @if($model->details)
            @foreach($model->details as $transaction)
                <tr>
                    <td>{{$transaction['account_code_id']}}</td>
                    <td>{{$transaction->account_code->name}}</td>
                    <td class="text-right">{{number_format($transaction['credit'])}}</td>
                    <td>{{$transaction['mutation'] ? 'Yes' : 'No'}}</td>
                    <td>{{$transaction['note']}}</td>
                    <td>
                        <a class="act-delete" data-url="{{url('cash_outs/actions/delete')}}"
                           data-id="{{$transaction['id']}}" href="javascript:void(0)"><span class="glyphicon glyphicon-remove"></span></a>
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
<div class="col-md-6 pull-md-right" style="margin-top: 10px">
    <div class="form-group col-md-6">
        <label class="form-control-label">Account</label>
        <select name="account_cash_id" class="form-control">
            @foreach($cashes as $cash)
                @if($model->account_cash_id == $cash->id)
                    <option selected value="{{$cash->id}}">{{$cash->name}}</option>
                @else
                    <option value="{{$cash->id}}">{{$cash->name}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">Total</label>
        <input type="text" readonly="" id="total" name="total" value="{{number_format($model->total)}}" class="form-control">
    </div>
</div>
<script type="text/html" id="row-cash">
    <tr>
        <td data-content="account_code_id"></td>
        <td data-content="name"></td>
        <td class="text-right credit" data-content="credit" data-format="currency"></td>
        <td data-content="mutation"></td>
        <td data-content="note"></td>
        <td>
            <a class="act-delete" data-url="{{url('cash_outs/actions/delete')}}"
               data-template-bind='[{"attribute": "data-id", "value": "id"}]' href="javascript:void(0)">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
        </td>
    </tr>
</script> 