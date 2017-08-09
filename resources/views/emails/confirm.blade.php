@extends('default')

@section('content')
    {!! csrf_field() !!}
    <body>
        <h1> Thanks for signing up!</h1>

        <p>
            Hi {{$user->name}}, Thank you for registring with www.grabmyswag.com'.
            You are almost ready to get free swag from awesome ccompanies.
            Please confirm your email here <a href='{{url("register/confirm/{$user->token}")}}'>. On clicking the link show message that confirmation was successful.
        </p>
    </body>

@endsection

