<div class="btn-group btn-group-sm">
    @permission(('edit.products'))
    <a title="edit" href="{{url('products/pricing/'.$id)}}" class="btn btn-sm btn-info"><span
            class="glyphicon glyphicon-usd"></span></a>
    <a title="edit" href="{{url('products/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.products'))
    <a title="delete" data-url="{{url('products/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>