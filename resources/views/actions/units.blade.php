<div class="btn-group btn-group-sm">
    @permission(('edit.units'))
    <a title="edit" href="{{url('units/components/'.$id)}}" class="btn btn-sm btn-success"><span
            class="glyphicon glyphicon-list"></span></a>
    <a title="edit" href="{{url('units/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.units'))
    <a title="delete" data-url="{{url('units/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>