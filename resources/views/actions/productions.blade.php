@if($state_id != 3)
    <div class="btn-group btn-group-sm">
        @permission(('edit.productions'))
        <a title="edit" href="{{url('productions/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
                    class="glyphicon glyphicon-check"></span></a>
        @endpermission
        @permission(('delete.productions'))
        <a title="delete" data-url="{{url('productions/delete/'.$id)}}"
           class="btn btn-sm btn-danger delete-action"><span
                    class="glyphicon glyphicon-trash"></span></a>
        @endpermission
        <a title="invoice" id="set-finish" href="{{url('productions/actions/complete/'.$id)}}"
           class="btn btn-sm btn-success">
            <span class="glyphicon glyphicon-ok"></span> Complete</a>
    </div>
@endif