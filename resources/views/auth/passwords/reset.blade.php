@extends('layouts.auth')
@section('content')
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" method="post" action="{{ url('password/reset') }}">
                    <div class="sign-avatar"><img src="{{ asset('img/avatar-sign.png') }}"></div>
                    <header class="sign-title">Reset Password</header>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
                        <input type="email" class="form-control" placeholder="Email" name="email"
                               value="{{ old('email') }}" required autofocus/>
                        <input type="hidden" name="token" value="{{ $token }}">
                        {{ csrf_field() }}
                        @if ($errors->has('email'))
                            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                        <input type="password" class="form-control" placeholder="Password" name="password" required/>
                        @if ($errors->has('password'))
                            <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                        <input type="password" class="form-control" placeholder="Confirm Password"
                               name="password_confirmation" required/>
                        @if ($errors->has('password_confirmation'))
                            <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-rounded">Reset Password</button>
                </form>
            </div>
        </div>
    </div><!--.page-center-->
@endsection
