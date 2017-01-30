<div class="btn-group btn-group-sm">
    @permission(('edit.discounts'))
    <a title="edit" href="{{url('discounts/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.discounts'))
    <a title="delete" data-url="{{url('discounts/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>