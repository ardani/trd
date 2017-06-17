@include('includes.alert')
<div class="row">
    <fieldset class="form-group col-md-3">
        <label class="form-control-label">Account Code <span class="text-danger">*</span></label>
        <select name="account_code_id" class="form-control select-account-code" data-live-search="true">
            @if($model)
                <option value="{{$model->account_code_id}}">{{$model->account_code->name}}</option>
            @endif
        </select>
    </fieldset>
    <fieldset class="form-group col-md-3">
        <label class="form-control-label">Debit <span class="text-danger">*</span></label>
        <input type="number" name="value" class="form-control"
               value="{{ $model ? $model['debit'] : old('debit', 0) }}"
               data-validation="[NOTEMPTY]">
        {{ csrf_field() }}
    </fieldset>
    <fieldset class="form-group col-md-3">
        <label class="form-control-label">Credit <span class="text-danger">*</span></label>
        <input type="number" name="credit" class="form-control"
               value="{{ $model ? $model['credit'] : old('credit', 0) }}"
               data-validation="[NOTEMPTY]">
    </fieldset>
    <fieldset class="form-group col-md-3">
        <label class="form-control-label">No Giro</label>
        <input class="form-control" name="giro" value="{{ $model ? $model['giro'] : old('giro') }}">
    </fieldset>
    <fieldset class="form-group col-md-12">
        <label class="form-control-label">Note</label>
        <textarea class="form-control" name="note" id="" cols="30"
                  rows="5">{{ $model ? $model['note'] : old('note') }}</textarea>
    </fieldset>
</div>