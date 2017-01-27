<div class="btn-group btn-group-sm">
    @permission(('edit.menus'))
        <a title="edit" href="{{url('menus/edit/'.$id)}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.menus'))
        <a title="delete" data-url="{{url('menus/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>