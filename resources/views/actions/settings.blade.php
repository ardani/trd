<div class="btn-group btn-group-sm">
    @permission(('edit.settings'))
    <a title="edit" href="{{url('settings/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.settings'))
    <a title="delete" data-url="{{url('settings/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>