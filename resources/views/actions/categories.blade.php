<div class="btn-group btn-group-sm">
    @permission(('edit.categories'))
    <a title="edit" href="{{url('categories/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.categories'))
    <a title="delete" data-url="{{url('categories/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>