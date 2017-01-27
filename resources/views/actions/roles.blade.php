<div class="btn-group btn-group-sm">
    @permission(('edit.roles'))
    <a title="role permission" href="{{url('roles/permissions/'.$id)}}" class="btn btn-sm btn-success"><span
            class="glyphicon glyphicon-lock"></span></a>
    <a title="edit" href="{{url('roles/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.roles'))
    <a title="delete" data-url="{{url('roles/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>