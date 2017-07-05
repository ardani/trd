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
                    <form action="{{url('report_sales/print')}}" method="get" id="freport-sales" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Shop</label>
                                    <select name="shop_id" class="form-control">
                                        <option value="">All</option>
                                        @foreach($shops as $shop)
                                            <option value="{{$shop->id}}">{{$shop->name}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Customer</label>
                                    <select name="customer_id" class="form-control select-customer" data-live-search="true">
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Date</label>
                                    <input type="text" name="date" id="date" class="form-control dateuntil" value="{{date('01/m/Y') .' - '.date('t/m/Y')}}">
                                    <input type="hidden" name="type" value="normal" id="type-print"/>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group" style="padding-top: 30px">
                                    <button type="button" id="bview" class="btn btn-success"> View</button>
                                    <button type="button" id="bprint" class="btn btn-primary"> Print</button>
                                    <button type="button" id="bexcel" class="btn btn-info"> Excel</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <section class="card">
                <div class="card-block">
                    <table class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Shop</th>
                            <th>Sale</th>
                            <th>Payment Info</th>
                            <th>Cash</th>
                            <th>Disc</th>
                            <th>Total</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; $total = 0;?>
                        @foreach($sales as $row)
                            <tr>
                                <td>{{$no}}</td>
                                <td>{{$row->shop->name}}</td>
                                <td>{{$row->no}} <br/> {{$row->customer->name}}</td>
                                <td>
                                    <ul>
                                        <li>
                                            <span class="label label-{{$row->payment_method->name == 'credit' ? 'danger' : 'success'}}">
                                                {{$row->payment_method->name}}</span>
                                            @if ($row->paid_status)
                                                <span class="label label-success">paid</span>
                                            @endif
                                        </li>
                                        @if ($row->payment_method_id == 2 && $row->paid_status != 1)
                                            <li>expire :{{ is_null($row->paid_until_at) ? '-' : $row->paid_until_at->format('d/m/Y')}}</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="text-right">{{number_format($row->cash)}}</td>
                                <td class="text-right">{{number_format($row->disc)}}</td>
                                <td class="text-right">{{number_format($row->total)}}</td>
                                <td>{{$row->created_at->format('d M Y')}}</td>
                            </tr>
                            <?php $no++; $total += $row->total?>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">TOTAL</td>
                                <td class="text-right">{{number_format($total)}}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/report-sales.js')}}"></script>
@endsection