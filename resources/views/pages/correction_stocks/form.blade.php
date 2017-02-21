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
    <label class="form-control-label">Correction Stock <span class="text-danger">*</span></label>
    <input type="text" name="correction" class="form-control"
           value="{{ $model ? $model['correction'] : old('correction') }}"
           data-validation="[NOTEMPTY]"
           placeholder="correction">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Purchase Price <span class="text-danger">*</span></label>
    <input type="text" name="purchase_price" class="form-control"
           value="{{ $model ? $model['purchase_price'] : old('purchase_price') }}"
           data-validation="[NOTEMPTY]"
           placeholder="purchase price">
        {{ csrf_field() }}
</fieldset>