@if(session('message'))
    {!! alerts('success',session('message')) !!}
@endif
<fieldset class="form-group">
    <label class="form-control-label">Key <span class="text-danger">*</span></label>
    <input type="text" name="key" class="form-control"
           value="{{ $model ? $model['key'] : old('key') }}"
           data-validation="[NOTEMPTY]"
           placeholder="key">
        {{ csrf_field() }}
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control"
           value="{{ $model ? $model['name'] : old('name') }}"
           data-validation="[NOTEMPTY]"
           placeholder="name">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Value <span class="text-danger">*</span></label>
    <input type="text" name="value" class="form-control"
           value="{{ $model ? $model['value'] : old('value') }}"
           data-validation="[NOTEMPTY]"
           placeholder="value">
</fieldset>