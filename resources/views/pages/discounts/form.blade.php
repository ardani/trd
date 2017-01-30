@include('includes.alert')
<div class="col-md-4">
    <fieldset class="form-group">
        <label class="form-control-label">Product <span class="text-danger">*</span></label>
        <select name="product_id" class="form-control bootstrap-select" data-live-search="true">
        </select>
        {{ csrf_field() }}
    </fieldset>
</div>
<div class="col-md-4">
    <fieldset class="form-group">
        <label class="form-control-label">Amount <span class="text-danger">*</span></label>
        <input type="text" name="amount" class="form-control"
               value="{{ $model ? $model['amount'] : old('amount') }}"
               data-validation="[NOTEMPTY]"
               placeholder="amount">
    </fieldset>
</div>
<div class="col-md-4">
    <fieldset class="form-group">
        <label class="form-control-label">Expired At <span class="text-danger">*</span></label>
        <input type="text" name="expired_at" class="form-control daterange"
               value="{{ $model ? $model['expired_at']->format('d/m/Y') : old('expired_at') }}"
               data-validation="[NOTEMPTY]"
               placeholder="expired at">
    </fieldset>
</div>