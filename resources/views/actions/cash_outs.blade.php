<div class="btn-group btn-group-sm">
    @permission(('edit.cash_outs'))
    <a title="edit" href="{{url('cash_outs/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.cash_outs'))
    <a title="delete" data-url="{{url('cash_outs/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>