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
            @permission(('create.payment_orders'))
            <section class="card">
                <div class="card-block">
                    <a href="{{url('payment_orders/detail/'.$order_id.'/create')}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> New</a>
                    <a href="{{url('payment_orders')}}" class="btn btn-grey btn-sm pull-right">Back</a>
                </div>
            </section>
            @endpermission
            <section class="card">
                <div class="card-block">
                    <table id="table-payment-detail" data-url="{!! url(request()->path()) !!}" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Amount</th>
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