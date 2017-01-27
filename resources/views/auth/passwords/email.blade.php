@extends('layouts.auth')
<!-- Main Content -->
@section('content')
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" method="post" action="{{ url('/password/email') }}">
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
                        {{ csrf_field() }}
                        @if ($errors->has('email'))
                            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-rounded">Send Password Reset Link</button>
                </form>
            </div>
        </div>
    </div><!--.page-center-->
@endsection
