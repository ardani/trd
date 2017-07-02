<div class="btn-group btn-group-sm">
    @permission(('delete.take_products'))
    <a title="delete" data-url="{{url('take_products/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>