@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Create Component {{$name}} - {{ $unit->name }}</h3>
                        </div>
                    </div>
                </div>
            </header>
            <div class="box-typical box-typical-padding">
                <form id="formValid" method="post" action="{{ url('units/components/'.$unit->id.'/create') }}">
                    @include('pages.component_units.form',['model' => ''])
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a href="{{ url('units/components/'.$unit->id) }}" class="btn btn-default">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection