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
                    <form action="{{url('payment_sales/print')}}" method="get">
                    <div class="form-group col-md-3">
                        <label for="">Toko</label>
                        <select name="shop_id" class="form-control">
                            <option value="">-</option>
                            @foreach($shops as $shop)
                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control select-customer"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date</label>
                        <input type="text" name="date" id="date" class="form-control dateuntil" value="{{ date('01/m/Y') .' - '.date('t/m/Y')}}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="button" class="btn btn-primary btn-filter" style="margin-top: 20px">Filter</button>
                        <button type="submit" name="type" value="print" class="btn btn-info btn-print" style="margin-top: 20px">Print</button>
                        <button type="submit" name="type" value="excel" class="btn btn-success btn-excel" style="margin-top: 20px">Excel</button>
                    </div>
                    </form>
                </div>
            </section>
            <section class="card">
                <div class="card-block">
                    <table id="table-payment-sale" data-url="{!! url(request()->path()) !!}" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Sale No</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Remain</th>
                            <th>Status</th>
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
        $('#table-payment-sale').DataTable().ajax.reload();
    });
</script>
@endsection