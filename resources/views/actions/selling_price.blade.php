<div class="tbl">
    <div class="tbl-row">
        <div class="tbl-cell tbl-cell-lbl">Default:</div>
        <div class="tbl-cell">{{ number_format($model->selling_price_default) }}</div>
    </div>
    @foreach($model->product_price as $price)
    <div class="tbl-row">
        <div class="tbl-cell tbl-cell-lbl">{{$price->customer_type->name}}</div>
        <div class="tbl-cell">{{ number_format($price->selling_price) }}</div>
    </div>
    @endforeach
</div>