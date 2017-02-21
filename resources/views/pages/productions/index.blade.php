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