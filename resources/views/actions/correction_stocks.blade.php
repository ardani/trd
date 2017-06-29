<div class="btn-group btn-group-sm">
    @permission(('delete.correction_stocks'))
    <a title="delete" data-url="{{url('correction_stocks/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>