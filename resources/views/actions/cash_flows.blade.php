<div class="btn-group btn-group-sm">
    @permission(('edit.cash_flows'))
    <a title="edit" href="{{url('cash_flows/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.cash_flows'))
    <a title="delete" data-url="{{url('cash_flows/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>