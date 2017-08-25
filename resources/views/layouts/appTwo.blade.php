<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="js/jquery.terminal-1.5.3.js"></script>
    <link href="css/jquery.terminal-1.5.3.css" rel="stylesheet"/>
    <script>
        jQuery(function($, undefined) {
            $('#term').terminal(function(command, term) {
                var cmd = $.terminal.parse_command(command);
                this.pause();
                $.post("/shell",{ '_token': $('meta[name=csrf-token]').attr('content'), method: cmd.name, args :cmd.args}, function (data) {
                    term.resume();
                    /*if (cmd.name == "cd"){
                        if (data['STS'] == true)
                            term.set_prompt(data['MSG']);
                        else
                            term.echo(data['MSG']);
                    }
                    else if (cmd.name == "request") {
                        term.echo("\nRequesting challenge...");
                        setTimeout(function () {
                            term.echo(data['MSG']);
                        }, 200);

                    }
                    else if (cmd.name == "clear") {
                        term.clear();
                    }
                    else if (cmd.name == "submit"){
                        //change prompt if succesfully submitted
                        if (data['STS'] == true)
                            term.set_prompt('<?php Auth::user()['name'] ?>@Castle:~$ ');
                        term.echo(data['MSG']);
                    }
                    else {
                        term.echo(data['MSG']);
                    }*/
                    switch (cmd.name){
                        case "cd":
                            if (data['STS'] == true)
                                term.set_prompt(data['MSG']);
                            else
                                term.echo(data['MSG']);
                            break;
                        case "request":
                            term.echo("\nRequesting challenge...");
                            setTimeout(function () {
                                term.echo(data['MSG']);
                            }, 200);
                            break;
                        case "clear":
                            term.clear();
                            break;
                        case "logout":
                            if (data['STS'] == true) {
                                term.echo(data['MSG']);
                                location.reload();
                            }
                            else{
                                term.echo(data['MSG']);
                            }
                            break;
                        default:
                            term.echo(data['MSG']);
                            break;

                    };

                });
            }, {
                tabcompletion: true,
                completion: function(command, callback) { ///write tab completion of files also iterating over $pwd here
                    callback(['cd', 'cat', 'clear', 'edit', 'help', 'ls', 'logout', 'request', 'status', 'submit', 'verify']);
                },
                greetings: 'Mounting /home/<?php echo Auth::user()['name']; ?>...',
                name: 'js',
                height: 200,
                prompt: '<?php echo Auth::user()['name'] ?>@Castle:<?php echo session('pwd')?>$ '
            });
        });
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
