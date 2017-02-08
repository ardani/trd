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
                        <h2 style="margin:0" id="charge">{{number_format($charge)}}</h2>
                    </div>
                </div>
            </div>
            <div class="box-typical box-typical-padding">
                <form id="form-po" data-url="{{ url($path.'/edit/'.$id) }}">
                    <div class="row">
                        @include('pages.'.$path.'.form-edit')
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/purchase_orders/create" class="btn btn-success"><span class="glyphicon glyphicon-file"></span> New</a>
                            <button type="button" id="save-pruchase-btn" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            <a href="javascript:void(0)" class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Print</a>
                            <a href="/purchase_orders" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection