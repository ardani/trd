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
        <label class="form-control-label">Debet <span class="text-danger">*</span></label>
        <input type="number" name="value" class="form-control"
               value="{{ $model ? $model['value'] : old('value') }}"
               data-validation="[NOTEMPTY]"
               placeholder="value">
        {{ csrf_field() }}
    </fieldset>
    <fieldset class="form-group col-md-12">
        <label class="form-control-label">Note</label>
        <textarea class="form-control" name="note" id="" cols="30"
                  rows="5">{{ $model ? $model['note'] : old('note') }}</textarea>
    </fieldset>
</div>