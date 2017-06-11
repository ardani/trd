<div class="btn-group btn-group-sm">
    @permission(('edit.payment_sales'))
    <a title="edit" href="{{url('payment_sales/detail/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-list"></span></a>
    @endpermission
    <a title="print" href="{{url('payment_sales/actions/print/'.$id)}}" class="btn btn-sm btn-warning"><span
            class="glyphicon glyphicon-print"></span> print</a>
</div>