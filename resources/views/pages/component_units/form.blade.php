@if(session('message'))
    {!! alerts('success',session('message')) !!}
@endif
<fieldset class="form-group">
    <label class="form-control-label">Code <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control"
           value="{{ $model ? $model['code'] : old('code') }}"
           data-validation="[NOTEMPTY]"
           placeholder="code">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control"
           value="{{ $model ? $model['name'] : old('name') }}"
           data-validation="[NOTEMPTY]"
           placeholder="name">
    <input type="hidden" name="unit_id" value="{{$unit->id}}"/>
        {{ csrf_field() }}
</fieldset>