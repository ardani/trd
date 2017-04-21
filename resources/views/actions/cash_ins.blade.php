<div class="btn-group btn-group-sm">
    @permission(('edit.cash_ins'))
    <a title="edit" href="{{url('cash_ins/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.cash_ins'))
    <a title="delete" data-url="{{url('cash_ins/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>