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
                <form id="formValid" method="post" action="{{ url($path.'/edit/'.$id) }}">
                    @include('pages.menus.form')
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="pull-right btn btn-default" href="{{url($path)}}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection