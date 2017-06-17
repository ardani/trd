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
            <section class="card">
                <div class="card-block">
                    <form action="{{url('cash_outs/print')}}" method="get">
                        <div class="form-group col-md-3">
                            <label for="">Date</label>
                            <input type="text" id="date" name="date" class="form-control dateuntil" value="{{ date('01/m/Y') .' - '.date('t/m/Y')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <button type="button" name="type" value="filter" class="btn btn-primary btn-filter" style="margin-top: 20px">Filter</button>
                            @permission(('create.'.$path))
                            <a href="{{url($path.'/create')}}" class="btn btn-success" style="margin-top: 20px"><span class="glyphicon glyphicon-plus"></span> New</a>
                            @endpermission
                        </div>
                    </form>
                </div>
            </section>
            <section class="card">
                <div class="card-block">
                    <table id="table-cash-outs" data-url="{!! url(request()->path()) !!}" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Cash No</th>
                            <th>Account Cash</th>
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
            $('#table-cash-outs').DataTable().ajax.reload();
        });
    </script>
@endsection