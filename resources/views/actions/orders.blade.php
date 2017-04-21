<div class="btn-group btn-group-sm">
    @permission(('edit.orders'))
    <a title="edit" href="{{url('orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.orders'))
    <a title="delete" data-url="{{url('orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action-note"><span
                class="glyphicon glyphicon-trash"></span></a>
    @endpermission
    <a href="#" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="glyphicon glyphicon-cog"></span>
    </a>
    <div class="dropdown-menu">
        <a title="invoice" href="{{url('return_orders/create/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-arrow-left"></span> Return Order</a>
        <a title="invoice" target="_blank" href="{{url('orders/actions/print/invoice/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-print"></span> Print Invoice</a>
    </div>
</div>