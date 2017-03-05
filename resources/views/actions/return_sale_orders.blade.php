<div class="btn-group btn-group-sm">
    @permission(('edit.return_sale_orders'))
    <a title="edit" href="{{url('return_sale_orders/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.return_sale_orders'))
    <a title="delete" data-url="{{url('return_sale_orders/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
        <a href="#" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-cog"></span>
        </a>
        <div class="dropdown-menu">
            <a title="complete" href="{{url('return_sale_orders/actions/complete/'.$id)}}" class="dropdown-item"><span
                    class="glyphicon glyphicon-print"></span> Set Complete</a>
            <a title="print" href="{{url('return_sale_orders/action/print/'.$id)}}" class="dropdown-item"><span
                    class="glyphicon glyphicon-print"></span> Print</a>
        </div>
</div>