<div class="btn-group btn-group-sm">
    @permission(('edit.account_codes'))
    <a title="edit" href="{{url('account_codes/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-pencil"></span></a>
    @endpermission
    @permission(('delete.account_codes'))
    <a title="delete" data-url="{{url('account_codes/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
</div>