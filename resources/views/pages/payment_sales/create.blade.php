@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Create {{$name}}</h3>
                        </div>
                    </div>
                </div>
            </header>
            <div class="box-typical box-typical-padding">
                <form id="formValid" method="post" action="{{ url($path.'/detail/'.$sale_id.'/create') }}">
                    @include('pages.'.$path.'.form',['model' => ''])
                    <input type="hidden" name="sale_id" value="{{$sale_id}}"/>
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a class="pull-right btn btn-default" href="{{url($path.'/detail/'.$sale_id)}}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/payment-sale.js')}}"></script>
@endsection