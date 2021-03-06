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
                    <form action="" method="get" id="freport-debts" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Supplier</label>
                                    <select name="supplier_id" class="form-control select-supplier" data-live-search="true">
                                        @if($supplier)
                                            <option selected value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endif
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Status</label>
                                    <select name="status" class="form-control">
                                        @foreach($statuses as $key => $row)
                                            <option {{$key == $status ? 'selected' : ''}} value="{{$key}}">{{$row}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-3">
                                <fieldset class="form-group">
                                    <label class="form-label semibold" for="exampleInput">Date</label>
                                    <input type="text" name="date" id="date" class="form-control dateuntil" value="{{$date}}">
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
                    <table id="table-debts" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Supplier</th>
                            <th>Paid Until At</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total = 0; $payment = 0;?>
                        @foreach($debts as $row)
                            <tr>
                                <td>{{$row->no}}</td>
                                <td>{{$row->supplier->name}}</td>
                                <td>{{$row->paid_until_at->format('d M Y')}}</td>
                                <td class="text-right">{{number_format($row->total)}}</td>
                                <td class="text-right">{{number_format(abs($row->payment->total))}}</td>
                                <td>{{$row->paid_status ? 'paid' : 'unpaid'}}</td>
                                <td>{{$row->created_at->format('d M Y')}}</td>
                            </tr>
                            <?php
                                $total += $row->total;
                                $payment += abs($row->payment->total);
                            ?>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">TOTAL</td>
                                <td class="text-right">{{number_format($total)}}</td>
                                <td class="text-right">{{number_format($payment)}}</td>
                                <td class="text-right">{{number_format($total-$payment)}}</td>
                                <td class="text-right"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/report-debt.js')}}"></script>
@endsection