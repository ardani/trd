<div class="btn-group btn-group-sm">
    @permission(('edit.productions'))
    <a title="edit" href="{{url('productions/edit/'.$id)}}" class="btn btn-sm btn-primary"><span
            class="glyphicon glyphicon-check"></span></a>
    @endpermission
    @permission(('delete.productions'))
    <a title="delete" data-url="{{url('productions/delete/'.$id)}}" class="btn btn-sm btn-danger delete-action"><span
            class="glyphicon glyphicon-trash"></span></a>
    @endpermission
    <a href="#" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="glyphicon glyphicon-cog"></span>
    </a>
    <div class="dropdown-menu">
        <a title="invoice" id="set-finish" href="{{url('productions/actions/finish/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-ok"></span> Finish</a>
        <a title="do" href="{{url('productions/actions/spk/'.$id)}}" class="dropdown-item"><span
                class="glyphicon glyphicon-print"></span> Print</a>
    </div>
</div>