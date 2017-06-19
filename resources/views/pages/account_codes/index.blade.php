@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h2>{{$name}}</h2>
                            <div class="subtitle">{{ $description }}</div>
                        </div>
                    </div>
                </div>
            </header>
            @permission(('create.'.$path))
                <section class="card">
                    <div class="card-block">
                        <a href="{{url($path.'/create')}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> New</a>
                    </div>
                </section>
            @endpermission
            <section class="card">
                <div class="card-block">
                    <table id="table" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No Account</th>
                            <th>Name</th>
                            <th>Saldo</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($codes as $code)
                                <tr class="{{$code->parent ? 'parent' : ''}}">
                                    <?php $account_saldo = array_key_exists($code->id, $saldo) ? $saldo[$code->id]['saldo'] : 0; ?>
                                    <td>{{$code->id}}</td>
                                    <td colspan="{{$code->parent ? 3 : 1}}">{{$code->name}}</td>
                                    @if (!$code->parent)
                                        <td>{{number_format($account_saldo)}}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @permission(('edit.account_codes'))
                                                <a title="edit" href="{{url('account_codes/edit/'.$code->id)}}" class="btn btn-sm btn-primary"><span
                                                            class="glyphicon glyphicon-pencil"></span></a>
                                                @endpermission
                                                @permission(('delete.account_codes'))
                                                <a title="delete" data-url="{{url('account_codes/delete/'.$code->id)}}" class="btn btn-sm btn-danger delete-action"><span
                                                            class="glyphicon glyphicon-trash"></span></a>
                                                @endpermission
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection