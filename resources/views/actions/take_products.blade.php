<div class="btn-group btn-group-sm">
    @permission(('edit.take_products'))
    <a title="edit" href="{{url('take_products/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.take_products'))
    <a title="delete" data-url="{{url('take_products/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>