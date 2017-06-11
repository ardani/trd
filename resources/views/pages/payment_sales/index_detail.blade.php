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
            @permission(('create.payment_sales'))
                <section class="card">
                    <div class="card-block">
                        <a href="{{url('payment_sales/detail/'.$sale_id.'/create')}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> New</a>
                    </div>
                </section>
            @endpermission
            <section class="card">
                <div class="card-block">
                    <table id="table-payment-detail" data-url="{!! url(request()->path()) !!}" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Value</th>
                            <th>Note</th>
                            <th>Giro</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection