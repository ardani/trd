@include('includes.alert')
<div class="col-md-12">
    <fieldset class="form-group">
        <label class="form-control-label">Product <span class="text-danger">*</span></label>
        <select name="product_id" class="form-control select-product" data-live-search="true">
            @if($model)
                <option value="{{$model->product_id}}">{{$model->product->code.' - '.$model->product->name}}</option>
            @endif
        </select>
    </fieldset>
    <fieldset class="form-group">
        <label class="form-control-label">Qty <span class="text-danger">*</span></label>
        <input type="text" name="qty" class="form-control"
               value="{{ $model ? $model['qty'] : old('qty') }}"
               data-validation="[NOTEMPTY]"
               placeholder="aty">
        {{ csrf_field() }}
    </fieldset>
</div>