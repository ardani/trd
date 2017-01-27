@extends('layouts.auth')

@section('content')
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" method="post" action="{{ '/login' }}">
                    <div class="sign-avatar"><img src="{{ asset('img/avatar-sign.png') }}"></div>
                    <header class="sign-title">Sign In</header>
                    <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}" >
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus/>
                        @if ($errors->has('email'))
                            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                        <input type="password" class="form-control" placeholder="Password" name="password" required/>
                        @if ($errors->has('password'))
                            <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                        @endif
                        {{ csrf_field() }}
                    </div>
                    <div class="form-group">
                        <div class="checkbox float-left">
                            <input type="checkbox" id="signed-in" name="remember" {{ old('remember') ? 'checked' : ''}}/>
                            <label for="signed-in">Keep me signed in</label>
                        </div>
                        <div class="float-right reset">
                            <a href="{{ url('password/reset') }}">Reset Password</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-rounded">Sign in</button>
                </form>
            </div>
        </div>
    </div><!--.page-center-->
@endsection
@section('scripts')
    <script>
        $(function () {
            $('.page-center').matchHeight({
                target: $('html')
            });
            $(window).resize(function () {
                setTimeout(function () {
                    $('.page-center').matchHeight({remove: true});
                    $('.page-center').matchHeight({
                        target: $('html')
                    });
                }, 100);
            });
        });
    </script>
@endsection
