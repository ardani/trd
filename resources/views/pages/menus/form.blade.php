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
    <label class="form-control-label">Path <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="path"
           value="{{ $model ? $model['path'] : old('path') }}"
           data-validation="[NOTEMPTY]"
           placeholder="path">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Icon <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="icon"
           value="{{ $model ? $model['icon'] : old('icon') }}"
           data-validation="[NOTEMPTY]"
           placeholder="icon">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Class <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="class"
           value="{{ $model ? $model['class'] : old('class') }}"
           data-validation="[NOTEMPTY]"
           placeholder="class">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Desc</label>
    <input type="text" class="form-control" name="description"
           value="{{ $model ? $model['description'] : old('description') }}"
           placeholder="desc">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Order</label>
    <input type="text" class="form-control" name="order"
           value="{{ $model ? $model['order'] : old('order') }}"
           placeholder="order">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Parent</label>
    <select name="parent" class="form-control">
        <option value="0"> default </option>
        @foreach($parents as $parent)
            @if($parent->id == safe_array($model,'parent'))
                <option selected value="{{$parent->id}}">{{$parent->name}}</option>
                @else
                <option value="{{$parent->id}}">{{$parent->name}}</option>
            @endif
        @endforeach
    </select>
</fieldset>