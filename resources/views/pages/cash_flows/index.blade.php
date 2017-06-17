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
                            <select name="account_code_id" class="form-control select-account-code" data-live-search="true">
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
                            <th>No</th>
                            <th>Account Name</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Saldo</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($cashes)
                                @foreach($cashes as $cash)
                                    <tr>
                                        <td>{{$cash->account_code_id}}</td>
                                        <td>{{$cash->account_code->name}}</td>
                                        <td>{{number_format($cash->sdebit)}}</td>
                                        <td>{{number_format($cash->scredit)}}</td>
                                        <td>{{number_format($cash->saldo)}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection