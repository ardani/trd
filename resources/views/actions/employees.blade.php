<div class="btn-group btn-group-sm">
    @permission(('edit.employees'))
    <a title="edit" href="{{url('employees/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.employees'))
    <a title="delete" data-url="{{url('employees/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>