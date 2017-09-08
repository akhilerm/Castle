<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Castle</title>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">

    @section('css')
    @show

    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>

    @section('topscript')
    @show

</head>
<body style="background-color: #161e21">
<nav class="grey darken-3">
                <div class="nav-wrapper">

            <!-- Branding Image -->
                    <a class="navbar-brand" style="position: absolute; left: 47.5%; font-weight: bolder; font-size: 25px; color: #00979c" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>


            <!-- Right Side Of Navbar -->
            <!-- Authentication Links -->
                @if (Auth::guest())
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                @endif
                </div>
        </nav>
        <!--Content-->
        @yield('content')

    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/js/materialize.min.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-96528618-3', 'auto');
        ga('send', 'pageview');

    </script>
        @section('bottomscript')
        @show
</body>
</html>
