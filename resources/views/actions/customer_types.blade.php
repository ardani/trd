<div class="btn-group btn-group-sm">
    @permission(('edit.customer_types'))
    <a title="edit" href="{{url('customer_types/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.customer_types'))
    <a title="delete" data-url="{{url('customer_types/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>