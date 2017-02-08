<div class="btn-group btn-group-sm">
    @permission(('edit.purchase_orders'))
    <a title="edit" href="{{url('purchase_orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    <a title="do" href="{{url('purchase_orders/do/'.$id)}}" class="btn btn-sm btn-info"><span
            class="glyphicon glyphicon-print"></span></a>
    <a title="invoice" href="{{url('purchase_orders/invoice/'.$id)}}" class="btn btn-sm btn-warning"><span
            class="glyphicon glyphicon-print"></span></a>
    @permission(('delete.purchase_orders'))
    <a title="delete" data-url="{{url('purchase_orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>