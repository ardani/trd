@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding">
                <form id="form-request" data-url="{{ url($path.'/create') }}" onsubmit="return false">
                    <div class="row">
                        @include('pages.'.$path.'.form-create',['model' => ''])
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/request_products/create" class="btn btn-success"><span class="glyphicon glyphicon-file"></span> New</a>
                            <button type="button" id="save-request-btn" data-redirect="{{url('request_products')}}" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            <a href="/request_products" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/request-products.js')}}"></script>
@endsection