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
                    <div class="form-group col-md-3">
                        <label for="">Status</label>
                        <select name="status" id="status" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Date</label>
                        <input type="text" name="range" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="button" class="btn btn-primary" style="margin-top: 20px">Filter</button>
                    </div>
                </div>
            </section>
            <section class="card">
                @include('includes.alert')
                <div class="card-block">
                    <table id="table-productions" data-url="{!! url(request()->path()) !!}" class="display table table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No PO</th>
                            <th>Status</th>
                            <th>No Production</th>
                            <th>Note</th>
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