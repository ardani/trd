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
                    <form action="{{url('report_profits/print')}}" method="get" id="freport-profits" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Date</label>
                                    <input type="text" name="date" id="date" class="form-control datesingle" value="{{$date}}">
                                    <input type="hidden" name="type" value="normal" id="type-print"/>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group" style="padding-top: 30px">
                                    <button type="button" id="bview" class="btn btn-success"> View</button>
                                    <button type="button" id="bprint" class="btn btn-primary"> Print</button>
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
                            <th>Description</th>
                            <th width="15%">Debit</th>
                            <th width="15%">Credit</th>
                            <th width="15%">Saldo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="font-weight: bold">Income</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 25px">Sale</td>
                            <td class="text-right" style="padding-left: 25px">{{number_format($sales_total)}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Total Income</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($sales_total)}}</td>
                            <?php $profit += $sales_total ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">HPP</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 25px">Persedian Awal</td>
                            <td></td>
                            <td class="text-right">{{number_format($first_stock)}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 25px">Order</td>
                            <td></td>
                            <td class="text-right">{{number_format($order_total)}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 25px">Persediaan Akhir</td>
                            <td></td>
                            <td class="text-right">{{number_format($last_stock)}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Total HPP</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($first_stock+$order_total-$last_stock)}}</td>
                            <?php $profit += ($first_stock+$order_total-$last_stock) ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Biaya Production</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($production_total)}}</td>
                            <?php $profit += $production_total ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Outcome</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php $total_cost = 0 ?>
                        @foreach($costs as $cost)
                            <tr>
                                <td style="padding-left: 25px;">{{$cost->name}}</td>
                                <td></td>
                                <td class="text-right">{{number_format($cost->saldo)}}</td>
                                <td></td>
                            </tr>
                            <?php $total_cost += $cost->saldo ?>
                        @endforeach
                        <tr>
                            <td style="font-weight: bold">Total Cost</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($total_cost)}}</td>
                            <?php $profit += $total_cost ?>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">Profit</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($profit)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/report-profits.js')}}"></script>
@endsection