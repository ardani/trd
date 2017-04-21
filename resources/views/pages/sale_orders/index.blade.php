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
                    <div class="form-group col-md-3">
                        <label for="">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control select-customer"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Status</label>
                        <select name="state_id" id="state_id" class="form-control">
                            <option value="">-</option>
                            <option value="1">PENDING</option>
                            <option value="2">PROCESS</option>
                            <option value="3">COMPLETED</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date</label>
                        <input type="text" id="date" class="form-control dateuntil" value="{{ date('01/m/Y') .' - '.date('t/m/Y')}}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="button" class="btn btn-primary btn-filter" style="margin-top: 20px">Filter</button>
                    </div>
                </div>
            </section>
            <section class="card">
                <div class="card-block">
                    <table id="table-po" data-url="{!! url(request()->path()) !!}" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Payment Info</th>
                            <th>Cash</th>
                            <th>Disc</th>
                            <th>Total</th>
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
@section('scripts')
    <script type="text/javascript">
        $('.btn-filter').click(function (e) {
            console.log('test');
            $('#table-po').DataTable().ajax.reload();
        });
    </script>
@endsection