@include('includes.alert')
<div class="row">
    <fieldset class="form-group col-md-6">
        <label class="form-control-label">Account Code <span class="text-danger">*</span></label>
        <select name="account_code_id" class="form-control select-account-code" data-live-search="true">
            @if($model)
                <option value="{{$model->account_code_id}}">{{$model->account_code->name}}</option>
            @endif
        </select>
    </fieldset>
    <fieldset class="form-group col-md-6">
        <label class="form-control-label">Order<span class="text-danger">*</span></label>
        <input type="hidden" name="ref_type" value="order"/>
        <select name="ref_id" class="form-control select-order" data-live-search="true">
        </select>
    </fieldset>
    <fieldset class="form-group col-md-6">
        <label class="form-control-label">Value <span class="text-danger">*</span></label>
        <input type="number" name="value" class="form-control"
               value="{{ $model ? $model['value'] : old('value') }}"
               data-validation="[NOTEMPTY]"
               placeholder="value">
        {{ csrf_field() }}
    </fieldset>
    <fieldset class="form-group col-md-6">
        <label class="form-control-label">No Giro</label>
        <input class="form-control" name="giro" value="{{ $model ? $model['giro'] : old('giro') }}">
    </fieldset>
    <fieldset class="form-group col-md-12">
        <label class="form-control-label">Note</label>
        <textarea class="form-control" name="note" id="" cols="30"
                  rows="10">{{ $model ? $model['note'] : old('note') }}</textarea>
    </fieldset>
</div>