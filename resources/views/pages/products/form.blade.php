@include('includes.alert')
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Name <span class="text-danger">*</span></label>
        {{ csrf_field() }}
        <input type="text" name="name" class="form-control"
               value="{{ $model ? $model['name'] : old('name') }}"
               data-validation="[NOTEMPTY]"
               placeholder="name">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Start Stock</label>
        <input type="number" name="start_stock" class="form-control"
               value="{{ $model ? $model['start_stock'] : old('start_stock') }}"
               placeholder="start stock">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Min Stock</label>
        <input type="number" name="min_stock" class="form-control"
               value="{{ $model ? $model['start_stock'] : old('start_stock') }}"
               placeholder="min stock">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Selling Price Default <span class="text-danger">*</span></label>
        <input type="number" name="selling_price_default" class="form-control"
               value="{{ $model ? $model['selling_price_default'] : old('selling_price_default') }}"
               data-validation="[NOTEMPTY]"
               placeholder="selling price">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Purchase Price Default <span class="text-danger">*</span></label>
        <input type="number" name="purchase_price_default" class="form-control"
               value="{{ $model ? $model['purchase_price_default'] : old('purchase_price_default') }}"
               data-validation="[NOTEMPTY]"
               placeholder="purchase price">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Stock At</label>
        <input type="text" name="stock_at" class="form-control datepicker"
               value="{{ $model ? $stock_at : old('stock_at') }}"
               placeholder="stock at">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Category</label>
        <select name="category_id" class="form-control bootstrap-select">
            @foreach($categories as $category)
                @if($category->id == safe_array($model,'category_id'))
                    <option selected value="{{$category->id}}">{{$category->name}}</option>
                @else
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endif
            @endforeach
        </select>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Supplier</label>
        <select name="supplier_id" class="form-control bootstrap-select">
            <option value="0"> -</option>
            @foreach($suppliers as $supplier)
                @if($supplier->id == safe_array($model,'supplier_id'))
                    <option selected value="{{$supplier->id}}">{{$supplier->name}}</option>
                @else
                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                @endif
            @endforeach
        </select>
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label"><input type="checkbox" name="can_sale" value="1"
                    {{ $model ? $model['can_sale'] == 1 ? 'checked' : '' : 'checked'}}> Can Sale</label>
    </fieldset>
</div>
<section class="tabs-section">
    <div class="tabs-section-nav tabs-section-nav-inline">
        <ul class="nav" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#tabs-4-tab-1" role="tab" data-toggle="tab">
                    Attributes
                </a>
            </li>
        </ul>
    </div><!--.tabs-section-nav-->

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="tabs-4-tab-1">
            <div class="row">
                @foreach($units as $unit)
                    <div class="col-md-4">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <div class="radio radioui">
                                    <input type="radio" value="{{$unit->id}}"
                                           name="unit_id" {{array_key_exists($unit->id,$product_units) ? 'checked' : ''}}>
                                    <label for="">{{$unit->name}}</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            @foreach($unit->component_unit as $component)
                                <fieldset class="form-group">
                                    <label class="form-control-label">{{$component->name}}</label>
                                    <input type="text" class="form-control"
                                           name="component[{{$unit->id}}][{{$component->code}}]"
                                           value="{{ @$product_units[$unit->id][$component->code] }}"/>
                                </fieldset>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div><!--.tab-pane-->
    </div><!--.tab-content-->
</section><!--.tabs-section-->