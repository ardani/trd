@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding">
                <div class="row">
                    <div class="col-md-2">
                        <h2 style="margin:0">Total</h2>
                        <h2 style="margin:0">Charge</h2>
                    </div>
                    <div class="col-md-3">
                        <h2 style="margin:0">Rp.</h2>
                        <h2 style="margin:0">Rp.</h2>
                    </div>
                    <div class="col-md-7 text-right">
                        <h2 style="margin:0" id="total">{{number_format($total)}}</h2>
                        <h2 style="margin:0" id="charge">0</h2>
                    </div>
                </div>
            </div>
            <div class="box-typical box-typical-padding">
                <form id="form-order" data-url="{{ url($path.'/create') }}" onsubmit="return false">
                    <div class="row">
                        @include('pages.'.$path.'.form-create',['model' => ''])
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/orders/create" class="btn btn-success"><span class="glyphicon glyphicon-file"></span> New</a>
                            <button type="button" id="save-order-btn" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            <a href="javascript:void(0)" class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Print</a>
                            <a href="/orders" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/orders.js')}}"></script>
@endsection