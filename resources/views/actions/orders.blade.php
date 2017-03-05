<div class="btn-group btn-group-sm">
    @permission(('edit.orders'))
    <a title="edit" href="{{url('orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    <a href="#" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="glyphicon glyphicon-cog"></span>
    </a>
    <div class="dropdown-menu">
        <a title="invoice" href="{{url('return_orders/create/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-arrow-left"></span> Return Order</a>
        <a title="invoice" href="{{url('order/invoice/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-print"></span> Print Invoice</a>
        @permission(('delete.orders'))
        <a title="delete" href="{{url('orders/delete/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-trash"></span> Delete</a>
        @endpermission
    </div>
</div>