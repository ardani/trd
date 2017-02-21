<div class="btn-group btn-group-sm">
    @permission(('edit.correction_stocks'))
    <a title="edit" href="{{url('correction_stocks/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.correction_stocks'))
    <a title="delete" data-url="{{url('correction_stocks/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>