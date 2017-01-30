<div class="btn-group btn-group-sm">
    @permission(('edit.products'))
    <a title="edit" href="{{url('product_prices/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.products'))
    <a title="delete" data-url="{{url('product_prices/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>