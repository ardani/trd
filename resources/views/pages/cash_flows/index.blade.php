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
                    <form method="get">
                        <div class="form-group col-md-3">
                            <label for="">Account</label>
                            <select name="account_code_id" class="form-control">
                                    <option value="">All</option>
                                @foreach($accounts as $account)
                                    <option value="{{$account->id}}">{{$account->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">Date</label>
                            <input type="text" id="date" name="date" class="form-control dateuntil" value="{{ date('01/m/Y') .' - '.date('t/m/Y')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <button type="submit" name="type" value="filter" class="btn btn-primary btn-filter" style="margin-top: 20px">Filter</button>
                            <button type="submit" name="type" value="print" class="btn btn-primary btn-filter" style="margin-top: 20px">Print</button>
                        </div>
                    </form>
                </div>
            </section>
            <section class="card">
                <div class="card-block">
                    <table class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Created</th>
                            <th>Cash No</th>
                            <th>Account</th>
                            <th>Note</th>
                            <th>Giro</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Saldo</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5"><strong>cash flow before</strong></td>
                                <td>{{number_format($cashes['last']['debit'])}}</td>
                                <td>{{number_format($cashes['last']['credit'])}}</td>
                                <td>{{number_format($cashes['last']['saldo'])}}</td>
                            </tr>
                            @if($cashes['present'])
                                <?php $saldo = $cashes['last']['saldo'];?>
                                @foreach($cashes['present'] as $cash)
                                    <?php $saldo += ($cash->debit - $cash->credit) ?>
                                    <tr>
                                        <td>{{$cash->created_at->format('d/M/Y')}}</td>
                                        <td>{{$cash->cash_id ? $cash->cash->no : '-'}}</td>
                                        <td>{{$cash->account_code->name}}</td>
                                        <td>{{$cash->note}}</td>
                                        <td>{{$cash->giro}}</td>
                                        <td>{{number_format($cash->debit)}}</td>
                                        <td>{{number_format($cash->credit)}}</td>
                                        <td>{{number_format($saldo)}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7"><strong>Last Saldo</strong></td>
                                <td>{{number_format($saldo)}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection