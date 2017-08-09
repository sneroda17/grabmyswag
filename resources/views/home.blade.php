@extends('default')

@section('content')
    <style>
        .dropbtn {
            background-color: #9a8e99;
            color: white;
            padding: 26px;
            font-size: 29px;
            border: none;
            cursor: pointer;
        }

        .dropbtn:hover, .dropbtn:focus {
            background-color: #9a8e99;
        }

        .dropdown {
            float: right;
            position: relative;
            display: inline-block;

        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            overflow: auto;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            right: 0;
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {background-color: #f1f1f1}

        .show {display:block;}
    </style>
<body class="home-page">
    <!-- Header -->
    <div id="header">

        <div id="logging">
            @if (Auth::check())
                <div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn">Menu</button>
                    <div id="myDropdown" class="dropdown-content">
                        <a class="button"   href={{url('/profile')}} > Profile</a>
                        <a class="button"   href={{url('/logout')}} > LogOut</a>
                    </div>
                </div>
              @else
                <a class="button"   href={{url('/login')}} > LogIn</a>
            @endif

        </div>

        <span class="logo icon fa-paper-plane-o"></span>
        <h1>Free Swag! Plain and Simple</h1>
        <p>You get free company swag  &  they get to share their message
            <br /> All you do is Sign Up and start collecting free swag.</p>
    </div>

    <!-- Main -->
    <div id="main">

        <header class="major container 75%">
            <h2>Companies want to Market and Launch
                <br />
                their brand/message.
                <br />
                You can help and get a free swag!</h2>
            <!--
            <p>Tellus erat mauris ipsum fermentum<br />
            etiam vivamus nunc nibh morbi.</p>
            -->
        </header>

        <div class="box alt container">
            <section class="feature left">
                <a href="#" class="image icon fa-signal"><img src="{{ asset('images/pic01.jpg') }}" alt="" /></a>
                <div class="content">
                    <h3>Step One</h3>
                    <p> Quickly Sign Up and tell us you are interested. Confirm using text or email and start collecting your swag locally.  </p>
                </div>
            </section>
            <section class="feature right">
                <a href="#" class="image icon fa-code"><img src="{{ asset('images/pic02.jpg') }}" alt="" /></a>
                <div class="content">
                    <h3>Step Two</h3>
                    <p>Companies launch their marketing/branding campaigns throughout the year. You are selected as target audience for different campaings and keep getting free swag all year round.</p>
                </div>
            </section>
            <section class="feature left">
                <a href="#" class="image icon fa-mobile"><img src="{{ asset('images/pic03.jpg') }}" alt="" /></a>
                <div class="content">
                    <h3>Step Three</h3>
                    <p>We send you a text/email to come collect your free swag from a local coffee shop/restraurant. You enjoy & flash your swag!</p>
                </div>
            </section>
        </div>

        <footer class="major container 75%">
            <h3>Its Plain an Simple</h3>
            <p>Flash your swag proudly and pay nothing for a cool branded swag. It is really a win-win situation where companies can effectively build a brand and you get free stuff.</p>
            <ul class="actions">
                <li><a href="{{ url('/register') }}" class="button">Sign Up Now</a></li>
            </ul>
        </footer>

    </div>

    <!-- Footer -->
    <div id="footer">
        <div class="container 75%">

            <header class="major last">
                <h2>Interested in Free Swag?</h2>
            </header>

            </p>


            <ul class="icons">
                <li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
                <li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
                <li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
                <li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
                <li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
            </ul>

            <ul class="copyright">
                <li>&copy; Untitled. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
            </ul>

        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/skel.min.js') }}"></script>
    <script src="{{ asset('js/util.js') }}"></script>
    <script src="{{ asset('js/jquery.slim.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.js') }}"></script>
    <script>
        /* When the user clicks on the button,
         toggle between hiding and showing the dropdown content */
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {

                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
@endsection