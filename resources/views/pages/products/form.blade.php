@include('includes.alert')
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Code <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control"
               value="{{ $model ? $model['code'] : old('code') }}"
               data-validation="[NOTEMPTY]"
               placeholder="code">
            {{ csrf_field() }}
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control"
               value="{{ $model ? $model['name'] : old('name') }}"
               data-validation="[NOTEMPTY]"
               placeholder="name">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Start Stock</label>
        <input type="text" name="start_stock" class="form-control"
               value="{{ $model ? $model['start_stock'] : old('start_stock') }}"
               placeholder="start stock">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Min Stock</label>
        <input type="text" name="min_stock" class="form-control"
               value="{{ $model ? $model['start_stock'] : old('start_stock') }}"
               placeholder="min stock">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Selling Price Default <span class="text-danger">*</span></label>
        <input type="text" name="selling_price_default" class="form-control"
               value="{{ $model ? $model['selling_price_default'] : old('selling_price_default') }}"
               data-validation="[NOTEMPTY]"
               placeholder="selling price">
    </fieldset>
</div>
<div class="col-md-6">
    <fieldset class="form-group">
        <label class="form-control-label">Purchase Price Default <span class="text-danger">*</span></label>
        <input type="text" name="purchase_price_default" class="form-control"
               value="{{ $model ? $model['purchase_price_default'] : old('purchase_price_default') }}"
               data-validation="[NOTEMPTY]"
               placeholder="purchase price">
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
            <option value="0"> - </option>
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
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label class="form-control-label">Units</label>
                    <select id="unit" data-url="{{url('product/unit/')}}" name="unit_id" class="form-control bootstrap-select" data-validation="[NOTEMPTY]">
                        <option value="0">-</option>
                        @foreach($units as $unit)
                            @if($unit->id == safe_array($model,'unit_id'))
                                <option selected value="{{$unit->id}}">{{$unit->name}}</option>
                            @else
                                <option value="{{$unit->id}}">{{$unit->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </fieldset>
            </div>
            <div class="col-md-6">
                    <div id="componentUnit">
                        @foreach($components as $component)
                            <div class="col-md-4">
                                <fieldset class="form-group">
                                    <label class="form-control-label">{{$component->component_unit->name}}</label>
                                    <input type="text" class="form-control" name="component[{{$component->component_unit_code}}]" value="{{$component->value}}" />
                                </fieldset>
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            </div>
            </div>
        </div><!--.tab-pane-->
    </div><!--.tab-content-->
</section><!--.tabs-section-->