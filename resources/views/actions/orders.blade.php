<div class="btn-group btn-group-sm">
    @permission(('edit.orders'))
    <a title="edit" href="{{url('orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.orders'))
    <a title="delete" data-url="{{url('orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action-note"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
    <a title="invoice" target="_blank" href="{{url('orders/actions/print/invoice/'.$id)}}" class="btn btn-sm btn-info"><span
            class="glyphicon glyphicon-print"></span></a>
</div>