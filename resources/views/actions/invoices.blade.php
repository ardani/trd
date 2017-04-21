<div class="btn-group btn-group-sm">
    @permission(('create.invoices'))
    <a title="print invoice" href="{{url('invoices/actions/print/invoice/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-print"></span> invoice</a>
    <a title="print do" data-url="{{url('invoices/print/delivery_order/'.$id)}}" class="btn btn-sm btn-warning"><span
            class="glyphicon glyphicon-print"></span> delivery order</a>
    @endpermission
</div>