@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h2>{{$name}} - {{$role->display_name}}</h2>
                            <div class="subtitle">{{ $description }}</div>
                        </div>
                    </div>
                </div>
            </header>
            <section class="card">
                <div class="card-block">
                    <form method="post" action="/roles/permissions/{{$role->id}}">
                        <div class="row">
                            @if(session('message'))
                                {!! alerts('success',session('message')) !!}
                            @endif
                            @foreach($permissions as $permission)
                            <div class="col-md-3 col-sm-6">
                                <div class="checkbox checkui">
                                    <input type="checkbox" name="permissions[]" value="{{$permission['id']}}" {{$permission['status'] ? 'checked' : ''}}/>
                                    <label>{{$permission['display_name']}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Update</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection