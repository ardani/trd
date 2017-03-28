<div class="btn-group btn-group-sm">
    @permission(('edit.payment_sale'))
    <a title="edit" href="{{url('payment_sale/detail/'.$id)}}" class="btn btn-sm btn-primary"><span
                class="glyphicon glyphicon-list"></span></a>
    @endpermission
    @permission(('delete.payment_sale'))
    <a title="delete" data-url="{{url('payment_sale/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
                class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>