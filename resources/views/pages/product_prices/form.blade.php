@include('includes.alert')
<div class="col-md-4">
<fieldset class="form-group ">
    <label class="form-control-label">Customer type</label>
    <select name="customer_type_id" class="form-control bootstrap-select">
        @foreach($types as $type)
            @if($type->id == safe_array($model,'customer_type_id'))
                <option selected value="{{$type->id}}">{{$type->name}}</option>
            @else
                <option value="{{$type->id}}">{{$type->name}}</option>
            @endif
        @endforeach
    </select>
</fieldset>
</div>
<div class="col-md-4">
<fieldset class="form-group">
    <label class="form-control-label">Selling price <span class="text-danger">*</span></label>
    <input type="text" name="selling_price" class="form-control"
           value="{{ $model ? $model['selling_price'] : old('selling_price') }}"
           data-validation="[NOTEMPTY]"
           placeholder="selling price">
        {{ csrf_field() }}
</fieldset>
</div>
<div class="col-md-4">
<fieldset class="form-group">
    <label class="form-control-label">Purchase price <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="purchase_price"
           value="{{ $model ? $model['purchase_price'] : old('purchase_price') }}"
           data-validation="[NOTEMPTY]"
           placeholder="purchase price">
</fieldset>
</div>