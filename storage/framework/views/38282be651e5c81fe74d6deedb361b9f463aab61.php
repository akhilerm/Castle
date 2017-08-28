<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Castle</title>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">

    <?php $__env->startSection('css'); ?>
    <?php echo $__env->yieldSection(); ?>

    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>

    <?php $__env->startSection('topscript'); ?>
    <?php echo $__env->yieldSection(); ?>

</head>
<body>
        <nav class="navbar">

            <!-- Branding Image -->
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <?php echo e(config('app.name', 'Laravel')); ?>

            </a>


            <!-- Right Side Of Navbar -->
            <!-- Authentication Links -->
                <?php if(Auth::guest()): ?>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                        <li><a href="<?php echo e(route('register')); ?>">Register</a></li>
                    </ul>
                <?php else: ?>
                    <a class='dropdown-button btn' href='#' data-activates='dropdown1'><?php echo e(Auth::user()->name); ?></a>
                    <ul id='dropdown1' class='dropdown-content right'>
                        <li><a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();"></a></li>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </ul>
                <?php endif; ?>
        </nav>
        <!--Content-->
        <?php echo $__env->yieldContent('content'); ?>

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
        <?php $__env->startSection('bottomscript'); ?>
        <?php echo $__env->yieldSection(); ?>
</body>
</html>
