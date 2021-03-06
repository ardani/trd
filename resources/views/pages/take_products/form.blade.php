@include('includes.alert')
<div class="row">
    <fieldset class="form-group col-md-3">
        <label class="form-control-label">Product <span class="text-danger">*</span></label>
        <select name="product_id" class="form-control select-product-raw" data-live-search="true">
        </select>
    </fieldset>
    <fieldset class="form-group col-md-2">
        <label class="form-control-label">Qty <span class="text-danger">*</span></label>
        <input type="text" name="qty" class="form-control"
               data-validation="[NOTEMPTY]"
               placeholder="qty">
        {{ csrf_field() }}
    </fieldset>
    <fieldset class="form-group col-md-4" id="units"></fieldset>
</div>