<div class="btn-group btn-group-sm">
    @permission(('edit.orders'))
    <a title="edit" href="{{url('orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    <a title="invoice" href="{{url('orders/invoice/'.$id)}}" class="btn btn-sm btn-warning"><span
            class="glyphicon glyphicon-print"></span></a>
    @permission(('delete.orders'))
    <a title="delete" data-url="{{url('orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>