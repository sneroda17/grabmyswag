@extends('default')

@section('content')
<body class="hold-transition login-page bg-green">
    <div class="login-box">
        <div class="login-logo">
            <span class="logo icon fa-paper-plane-o"></span>
            <a href="{{ url('/') }}"><b>SWAG</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <form action="{{ url('/login') }}" method="POST" id="login-form">

                @if( session('csrf_error'))
                    <div class="alert alert-warning" id="ajax-errors">
                        {{ session('csrf_error') }}
                    </div>
                @endif
                {!! csrf_field() !!}

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="text" placeholder="Email" name="email" id="email" class="form-control">
                    <span class="fa form-control-feedback"></span>
                </div>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" id="passwd" placeholder="Password" class="form-control">
                    <span class="fa form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <label>
                            <input type="checkbox" name="rememberme" value="1" checked> Remember Me
                        </label>
                    </div><!-- /.col -->
                </div>
                <div class="row margin">
                    <div class="12u">
                        <button class="btn btn-success btn-block" type="submit">Sign In</button>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="12u">
                        <a class="small" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                    </div>
                </div>
            </form>

            <div class="row text-center">
                {{--<hr>--}}
                <div class="col-xs-6">

                    <a href="{{ route('auth.forward', ['provider' => 'facebook']) }}" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i> <span class="hidden-xs">Use </span>Facebook</a>
                </div>
                <div class="col-xs-6">
                    <a href="{{ route('auth.forward', ['provider' => 'google']) }}" class="btn btn-block btn-social btn-google"><i class="fa fa-google-plus"></i> <span class="hidden-xs">Use </span>Google+</a>
                </div>
            </div>
            <!-- /.social-auth-links -->
            <div class="row margin">
                <br>
                <div class="12u">
                    <a href="{{ url('/register') }}" class="btn btn-outline btn-block">Register Here</a>
                </div>
            </div>

        </div><!-- /.login-box-body -->


    </div><!-- /.login-box -->

@endsection
