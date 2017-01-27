<div class="btn-group btn-group-sm">
    @permission(('edit.permissions'))
        <a title="edit" href="{{url('permissions/edit/'.$id)}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.permissions'))
        <a title="delete" data-url="{{url('permissions/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>