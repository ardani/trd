<div class="btn-group btn-group-sm">
    @permission(('edit.request_products'))
    <a title="edit" href="{{url('request_products/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.request_products'))
    <a title="delete" data-url="{{url('request_products/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action-note"><span
                class="glyphicon glyphicon-trash"></span></a>
    @endpermission
    <a title="invoice" target="_blank" href="{{url('request_products/actions/print/invoice/'.$id)}}" class="btn btn-sm btn-warning"><span
                class="glyphicon glyphicon-print"></span> Print Invoice</a>
</div>