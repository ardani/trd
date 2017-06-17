@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Edit {{$name}}</h3>
                        </div>
                    </div>
                </div>
            </header>
            <div class="box-typical box-typical-padding">
                <form id="form-cashins" data-url="{{ url($path.'/edit/'.$id) }}" onsubmit="return false">
                    <div class="row">
                        @include('pages.'.$path.'.form-edit')
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/cash_ins/create" class="btn btn-success"><span class="glyphicon glyphicon-file"></span> New</a>
                            <button type="button" id="save-cashins-btn" data-redirect="{{ url('cash_ins') }}" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            <a href="/cash_ins" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/cashins.js')}}"></script>
@endsection