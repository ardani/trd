<div class="btn-group btn-group-sm">
    @permission(('edit.units'))
    <a title="edit" href="{{url('units/components/'.$unit_id.'/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.units'))
    <a title="delete" data-url="{{url('units/components/'.$unit_id.'/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>