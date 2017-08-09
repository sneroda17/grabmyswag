@extends('default')

@section('content')
    <h1>Just To Be Safe...</h1>
    <p>
        Your account has been created, but we need to make sure you're a human
        in control of the phone number you gave us. Can you please enter the
        verification code we just sent to your phone?
    </p>
    <form action="{{ url('/user/verify') }}" method="POST" >
     <div class="form-group">
        {!! Form::label('token') !!}
        {!! Form::text('token', '', ['class' => 'form-control']) !!}
      </div>
    <button type="submit" class="btn btn-primary">Verify Token</button>
    </form>

    <hr>
    <form action="{{ url('/user/verify/resend') }}" method="POST" >
        <button type="submit" class="btn">Resend code</button>
    </form>
@endsection



