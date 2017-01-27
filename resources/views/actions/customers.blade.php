<div class="btn-group btn-group-sm">
    @permission(('edit.customers'))
    <a title="edit" href="{{url('customers/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.customers'))
    <a title="delete" data-url="{{url('customers/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>