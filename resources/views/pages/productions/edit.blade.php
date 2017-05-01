@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding">
                <form id="form-production" data-url="{{ url($path.'/edit/'.$id) }}">
                    <div class="row">
                        @include('pages.'.$path.'.form-edit')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/productions.js')}}"></script>
@endsection