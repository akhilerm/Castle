<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
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
                    if (cmd.name == "cd"){
                        if (data['STS'] == true)
                            term.set_prompt(data['MSG']);
                        else
                            term.echo(data['MSG']);
                    }
                    else if (cmd.name == "request") {
                        term.echo("\nRequesting challenge...\n");
                        setTimeout(function () {
                            term.echo(data.result);
                        }, 200);
                    }
                    else {
                        term.echo(data['MSG']);
                    }

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
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    <?php echo e(config('app.name', 'Laravel')); ?>

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
                    <?php if(Auth::guest()): ?>
                        <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                        <li><a href="<?php echo e(route('register')); ?>">Register</a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo e(csrf_field()); ?>

                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo $__env->yieldContent('content'); ?>
</div>

<!-- Scripts -->
<script src="<?php echo e(asset('js/app.js')); ?>"></script>
</body>
</html>
