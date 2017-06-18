<div class="btn-group btn-group-sm">
    @if ($account_code_id !='1000.01')
        @permission(('edit.payment_orders'))
        <a title="edit" href="{{url('payment_orders/detail/'.$payment_id.'/edit/'.$id)}}" class="btn btn-sm btn-primary">
            <span class="glyphicon glyphicon-pencil"></span></a>
        @endpermission
        @permission(('delete.payment_orders'))
        <a title="delete" data-url="{{url('payment_orders/detail/'.$payment_id.'/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
                class="glyphicon glyphicon-trash"></span></a>
        @endpermission
    @endif
</div>