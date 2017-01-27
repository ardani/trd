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
    <label class="form-control-label">Phone </label>
    <input type="text" class="form-control" name="phone"
           value="{{ $model ? $model['phone'] : old('phone') }}"
           placeholder="phone">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Address</label>
    <input type="text" class="form-control" name="address"
           value="{{ $model ? $model['address'] : old('address') }}"
           placeholder="address">
</fieldset>