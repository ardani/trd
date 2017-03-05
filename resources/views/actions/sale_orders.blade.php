<div class="btn-group btn-group-sm">
    @permission(('edit.sale_orders'))
    <a title="edit" href="{{url('sale_orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.sale_orders'))
    <a title="delete" data-url="{{url('sale_orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
        <a href="#" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-cog"></span>
        </a>
        <div class="dropdown-menu">
            <a title="invoice" href="{{url('return_sale_orders/create/'.$id)}}" class="dropdown-item"><span
                    class="glyphicon glyphicon-arrow-left"></span> Return Sale Order</a>
            <a title="do" href="{{url('sale_orders/do/'.$id)}}" class="dropdown-item"><span
                    class="glyphicon glyphicon-print"></span> Print Delivery Order</a>
            <a title="invoice" href="{{url('sale_orders/invoice/'.$id)}}" class="dropdown-item"><span
                    class="glyphicon glyphicon-print"></span> Print Invoice</a>
        </div>
</div>