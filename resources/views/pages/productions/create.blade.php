@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding">
                <form id="form-order" data-url="{{ url($path.'/create') }}" onsubmit="return false">
                    <div class="row">
                        @include('pages.'.$path.'.form-create',['model' => ''])
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="save-order-btn" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            <a href="javascript:void(0)" class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Print</a>
                            <a href="/productions" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/productions.js')}}"></script>
@endsection