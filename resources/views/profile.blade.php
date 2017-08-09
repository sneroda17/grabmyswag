@extends('default')

@section('content')

    <body class="hold-transition login-page bg-green">

    <div class="login-box">
        <div class="login-logo">
            <span class="logo icon fa-paper-plane-o"></span>
            <a href="{{ url('/') }}"><b>SWAG</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <h2 class="login-box-msg">Profile</h2>
            <form id="register-form" role="form" method="POST" action="{{ url('/update') }}">
                {!! csrf_field() !!}


                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
                    Name:*
                    <input type="text" placeholder="Full Name" name="name" value="{{$user->name}}" class="form-control">
                    <span class="fa  form-control-feedback"></span>
                </div>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    Email:*
                    <input type="text" placeholder="Email or Phone Number" id= "email" name="email" value="{{$user->email}}" class="form-control">
                    <span class="fa  form-control-feedback"></span>
                </div>


                <div class="form-group has-feedback{{ $errors->has('country') ? ' has-error' : '' }}">
                    Country:*
                    <select name="country" id="country" class="form-control" value="{{$user->country}}">
                        <option value="default">Select Country</option>
                        @foreach($countries as $country)
                            <option value={{ $country['id'] }}> {{$country['name']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group has-feedback">
                    Type City:(optional)
                    <input type="text" name="city" id="city" value="{{$user->city}}" class="form-control" >
                </div>

                <div class="form-group has-feedback" >
                    Zipcode:*
                    <input type="text" id="zip" name="zip" placeholder="Zipcode" value="{{$user->zip}}" class="form-control">
                </div>

                <div class="form-group has-feedback" >
                    Age:*
                    <input type="text" id="age" name="age" placeholder="Age" value="{{$user->age}}" class="form-control">
                </div>

                <div class="form-group has-feedback" >
                    Your feedback:
                    <input type="text" id="feedback" name="feedback" value="{{$user->category}}" class="form-control" >
                </div>

                <div class="form-group has-feedback" >
                    Select Education
                    <select name="education" id="education" class="form-control" value="{{$user->education}}">
                        <option value="">Select Education</option>
                        <option value="basic">Basic Education</option>
                        <option value="undergraduate">Undergraduate Degree</option>
                        <option value="master">Master </option>
                        <option value="higher">Higher Degree</option>
                    </select>
                </div>


                <div class="row margin">
                    <div class="12u">
                        <button class="btn btn-success btn-block register" type="submit">Submit <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>

            </form>
        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->



    <script>

        $(document).ready(function()
        {
            $("#country").editableSelect();
            var val = $("#education").attr("value");
            $("#education").val(val);
        });
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

        $("#email").focusout(function(){
            var email = $("#email").val();
            if(!isEmail(email))
                $(this).css("border-color", "red");
            else
                $(this).css("border-color",'white');
        });

        $("#zip").focusout(function () {
            if($.isNumeric($("#zip").val()))
                $(this).css("border-color",'white');
            else
                $(this).css("border-color", "red");
        });

        $("#age").focusout(function () {
            if($.isNumeric($("#age").val()))
                $(this).css("border-color",'white');
            else
                $(this).css("border-color", "red");
        });

        $("#country").editableSelect().on('select.editable-select', function(e , li)
        {
            //           $('#city').editableSelect('clear');
            //           var country = li.val();
//            if( !country)
//            {
//
//            }
//            else {
//                $.ajaxSetup({ headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                } });
//                $.ajax({
//                    method: "POST",
//                    url: "/getcountry",
//                    data: { country_id: country},
//                    cache: false,
//                    success: function(response)
//                    {
//                        response.forEach(function(city){
//                            $('#city').editableSelect('add',city);
//                        });
////                        sortSelect('#city', 'text', 'asc');
//                    },
//                    error:(function(e){
//
//                    })
//                });
//
//            }

        });
    </script>
@endsection

