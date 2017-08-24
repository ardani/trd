@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding">
                <form id="form-return-sales" data-url="{{ url($path.'/edit/'.$id) }}">
                    <div class="row">
                        @include('pages.'.$path.'.form-edit')
                    </div>
                    <div class="row">
                        <fieldset class="form-group col-md-12">
                            <label for="">Note</label>
                            <input type="text" name="note" class="form-control" value="{{$model->note}}">
                        </fieldset>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/return_sale_orders/create" class="btn btn-success"><span class="glyphicon glyphicon-file"></span> New</a>
                            <button type="button" id="save-return-sale-btn" data-redirect="{{url('return_sale_orders')}}" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
                            <a href="javascript:void(0)" class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Print</a>
                            <a href="/return_sale_orders" class="btn btn-default"><span class="glyphicon glyphicon-circle-arrow-left"></span> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/return-sales.js')}}"></script>
@endsection