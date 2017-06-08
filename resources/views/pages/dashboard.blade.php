@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 dahsboard-column">
                    <section class="box-typical box-typical-dashboard lobipanel panel panel-default scrollable">
                        <header class="box-typical-header panel-heading">
                            <h3 class="panel-title">Sale Due Date Before {{$date->format('d M Y')}}</h3>
                        </header>
                        <div class="box-typical-body panel-body">
                            <table class="tbl-typical">
                                <thead>
                                <tr>
                                    <th>
                                        <div>Status</div>
                                    </th>
                                    <th>
                                        <div>Customer</div>
                                    </th>
                                    <th align="center">
                                        <div>Sale#</div>
                                    </th>
                                    <th align="center">
                                        <div>Due Date</div>
                                    </th>
                                    <th align="left">Total</th>
                                    <th align="left">Payment</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payables as $row)
                                    <tr>
                                        <td>{{$row->payment->total >= $row->total ? 'paid' : 'unpaid'}}</td>
                                        <td>{{$row->customer->name}}</td>
                                        <td>{{$row->no}}</td>
                                        <td>{{$row->paid_until_at->format('d M Y')}}</td>
                                        <td>{{number_format($row->total)}}</td>
                                        <td>{{number_format($row->payment->total)}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div><!--.box-typical-body-->
                    </section><!--.box-typical-dashboard-->
                </div><!--.col-->
                <div class="col-xl-12 dahsboard-column">
                    <section class="box-typical box-typical-dashboard lobipanel panel panel-default scrollable">
                        <header class="box-typical-header panel-heading">
                            <h3 class="panel-title">Orders Due Date Before {{$date->format('d M Y')}}</h3>
                        </header>
                        <div class="box-typical-body panel-body">
                            <table class="tbl-typical">
                                <thead>
                                <tr>
                                    <th>
                                        <div>Status</div>
                                    </th>
                                    <th>
                                        <div>Supplier</div>
                                    </th>
                                    <th align="center">
                                        <div>Order#</div>
                                    </th>
                                    <th align="center">
                                        <div>Due Date</div>
                                    </th>
                                    <th align="left">Total</th>
                                    <th align="left">Payment</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($debts as $row)
                                        <tr>
                                            <td>{{$row->payment->total >= $row->total ? 'paid' : 'unpaid'}}</td>
                                            <td>{{$row->supplier->name}}</td>
                                            <td>{{$row->no}}</td>
                                            <td>{{$row->paid_until_at->format('d M Y')}}</td>
                                            <td>{{number_format($row->total)}}</td>
                                            <td>{{number_format($row->payment->total)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!--.box-typical-body-->
                    </section><!--.box-typical-dashboard-->
                </div><!--.col-->
            </div>
        </div><!--.container-fluid-->
    </div><!--.page-content-->
@endsection