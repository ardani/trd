@if(session('message'))
    {!! alerts('success',session('message')) !!}
@endif
<fieldset class="form-group">
    <label class="form-control-label">Product <span class="text-danger">*</span></label>
    <select name="product_id" class="form-control select-product" data-live-search="true">
        @if($model)
            <option value="{{$model->product_id}}">{{$model->product->code.' - '.$model->product->name}}</option>
        @endif
    </select>
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Units <span class="text-danger">*</span></label>
    <input type="text" name="attribute" class="form-control"
           value="{{ $model ? $model['attribute'] : old('attribute') }}"
           data-validation="[NOTEMPTY]"
           placeholder="units">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Qty <span class="text-danger">*</span></label>
    <input type="text" name="correction" class="form-control"
           value="{{ $model ? $model['qty'] : old('qty') }}"
           data-validation="[NOTEMPTY]"
           placeholder="qty">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Purchase Price <span class="text-danger">*</span></label>
    <input type="text" name="purchase_price" class="form-control"
           value="{{ $model ? $model['purchase_price'] : old('purchase_price') }}"
           data-validation="[NOTEMPTY]"
           placeholder="purchase price">
        {{ csrf_field() }}
</fieldset>