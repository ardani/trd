<div class="btn-group btn-group-sm">
    @permission(('edit.users'))
    <a title="edit" href="{{url('users/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.users'))
    <a title="delete" data-url="{{url('users/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>