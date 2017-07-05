<div class="btn-group btn-group-sm">
    @permission(('edit.shops'))
    <a title="edit" href="{{url('shops/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.shops'))
    <a title="delete" data-url="{{url('shops/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>