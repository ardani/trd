@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <section class="box-typical box-typical-dashboard lobipanel panel panel-default scrollable">
                        <header class="box-typical-header panel-heading">
                            <h3 class="panel-title">Update Password</h3>
                        </header>
                        <div class="box-typical box-typical-padding">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="post" action="{{url('profile')}}">
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Password Lama</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            <input type="password" required name="password_old" class="form-control" id="inputPassword" placeholder="password lama">
                                        </p>
                                        {{csrf_field()}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Password Baru</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">
                                            <input type="text" required name="password_new" class="form-control" id="inputPassword" placeholder="password lama">
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection