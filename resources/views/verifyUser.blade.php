@extends('default')


@section('content')
    <body>
    <div class="container">
        <div>
            <h1>Just To Be Safe...</h1>
            <p>
                Your account has been created, but we need to make sure you're a human
                in control of the phone number you gave us. Can you please enter the
                verification code we just sent to your phone?
            </p>
        </div>
        <div>
            <form method="post" action="{{ route('user-verify') }}">
                {!! csrf_field() !!}
                <div class="form-group">
                    {!! Form::label('token') !!}
                    {!! Form::text('token', '', ['class' => 'form-control']) !!}
                </div>
                <button type="submit" class="btn btn-primary">Verify Token</button>
            </form>
            <hr>
        </div>
        <div>
            <form method="post" action="{{ route('user-verify-resend') }}">
                {!! csrf_field() !!}
                <button type="submit" class="btn btn-primary">Resend code</button>
            </form>

        </div>
    </div>
    </body>
@endsection
