@if(session('message'))
    {!! alerts('success',session('message')) !!}
@endif
<fieldset class="form-group">
    <label class="form-control-label">No <span class="text-danger">*</span></label>
    <input type="text" name="id" class="form-control"
           value="{{ $model ? $model['id'] : old('id') }}"
           data-validation="[NOTEMPTY]"
           placeholder="no account">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control"
           value="{{ $model ? $model['name'] : old('name') }}"
           data-validation="[NOTEMPTY]"
           placeholder="name">
        {{ csrf_field() }}
</fieldset>