<div class="btn-group btn-group-sm">
    @permission(('edit.suppliers'))
    <a title="edit" href="{{url('suppliers/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.suppliers'))
    <a title="delete" data-url="{{url('suppliers/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>