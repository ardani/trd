<div class="btn-group btn-group-sm">
    @permission(('edit.take_product'))
    <a title="edit" href="{{url('take_product/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.take_product'))
    <a title="delete" data-url="{{url('take_product/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>