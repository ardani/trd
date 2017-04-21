@if($status_id != 3)
<div class="btn-group btn-group-sm">
    @permission(('edit.sale_orders'))
    <a title="edit" href="{{url('sale_orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.sale_orders'))
    <a title="delete" data-url="{{url('sale_orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action-note"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
    <a title="nota" target="_blank" href="{{url('sale_orders/actions/print/invoice/'.$id)}}" class="btn btn-sm btn-warning">
        <span class="glyphicon glyphicon-print"></span> invoice</a>
</div>
@else
    @permission(('create.sale_orders'))
    <a title="print invoice" target="_blank" href="{{url('sale_orders/actions/print/invoice/'.$id)}}" class="btn btn-sm btn-primary">
        <span class="glyphicon glyphicon-print"></span> invoice</a>
    <a title="print do" target="_blank" href="{{url('sale_orders/actions/print/do/'.$id)}}" class="btn btn-sm btn-warning"><span
                class="glyphicon glyphicon-print"></span> Do</a>
    @endpermission
@endif