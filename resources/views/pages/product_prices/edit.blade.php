@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Edit Product Price</h3>
                        </div>
                    </div>
                </div>
            </header>
            <div class="box-typical box-typical-padding">
                <form id="formValid" method="post" action="{{ url($path.'/edit/'.$id) }}">
                    <div class="row">
                        @include('pages.product_prices.form')
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Update</button>
                            <a class="pull-right btn btn-default" href="{{url($path)}}">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection