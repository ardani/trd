<div class="btn-group btn-group-sm">
    @permission(('edit.payment_order'))
    <a title="edit" href="{{url('payment_order/detail/'.$payment_id.'/edit/'.$id)}}" class="btn btn-sm btn-primary">
        <span class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.payment_order'))
    <a title="delete" data-url="{{url('payment_order/detail/'.$payment_id.'/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>