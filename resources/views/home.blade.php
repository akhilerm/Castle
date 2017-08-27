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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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
                        case "submit":
                            if (data['STS'] == true)
                                term.set_prompt('<?php echo Auth::user()['name'] ?>@Castle:~$ ');
                            term.echo(data['MSG']);
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
    <nav class="navbar navbar-default navbar-static-top">

    </nav>

    <!--Terminal-->
    <div class="container">

        <div class="row">
            <div id="term">
            </div>
        </div>

        <!--Editor-->
        <div class="editor row">


            <div class="row input-block">
                    <textarea id='input'>

                     </textarea>
            </div>

            <div class="navigation row">
                <div class="col s9">
                    <h5 class="white-text">File name</h5>
                </div>
                <div class="col s1">
                    <i class="material-icons">save</i>
                </div>
                <div class="col s1">
                    <i class="material-icons">done</i>
                </div>
                <div class="col s1">
                    <i class="material-icons">close</i>
                </div>
            </div>

        </div>
    </div>



<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/js/materialize.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

