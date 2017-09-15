<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Castle</title>
        <script type="text/javascript">
            <!--
            if (screen.width <= 699) {
            document.location = "http://www.google.com";
            }
            //-->
            </script>
        <!-- Fonts -->
       <link href="https://fonts.googleapis.com/css?family=VT323" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #161e21;
                color: #636b6f;
                font-family: 'VT323', monospace;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                color: #00979c;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
            <div class="title m-b-md">
                Castle
            </div>
            @if (Route::has('login'))
            <div class="links" style="color: white">
                    @if (Auth::check())
                <p><a style="color: white;" href="{{ url('/home') }}">Terminal</a></p>
                    @else
                <p>Been here before?
                    <a style="color: white;" href="{{ url('/login') }}">Login</a></p>
                <p>or
                    <a style="color: white;" href="{{ url('/register') }}">Register</a></p>
                    @endif
                </div>
            @endif
                
            </div>
        </div>
        <a href="https://github.com/akhilerm/Castle"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/52760788cde945287fbb584134c4cbc2bc36f904/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f77686974655f6666666666662e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_white_ffffff.png"></a>
    </body>
</html>
