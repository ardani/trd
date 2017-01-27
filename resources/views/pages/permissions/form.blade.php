@if(session('message'))
    {!! alerts('success',session('message')) !!}
@endif
<fieldset class="form-group">
    <label class="form-control-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control"
           value="{{ $model ? $model['name'] : old('name') }}"
           data-validation="[NOTEMPTY]"
           placeholder="name">
        {{ csrf_field() }}
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Display Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="display_name"
           value="{{ $model ? $model['display_name'] : old('display_name') }}"
           data-validation="[NOTEMPTY]"
           placeholder="Display Name">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Desc</label>
    <input type="text" class="form-control" name="description"
           value="{{ $model ? $model['description'] : old('description') }}"
           placeholder="desc">
</fieldset>