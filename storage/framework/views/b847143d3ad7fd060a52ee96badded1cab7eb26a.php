<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row">
        <div class="col s8 offset-s2">

            <form class="" method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo e(csrf_field()); ?>


                <div class="">
                    <label for="name" class="col s4">username</label>
                    <input id="name" type="text" class="" name="name" value="<?php echo e(old('name')); ?>" required autofocus>
                    <?php if($errors->has('name')): ?>
                        <span class="">
                            <strong><?php echo e($errors->first('name')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="">
                    <label for="password" class="col s4">Password</label>
                    <input id="password" type="password" class="" name="password" required>
                    <?php if($errors->has('password')): ?>
                        <span class="">
                                    <strong><?php echo e($errors->first('password')); ?></strong>
                                </span>
                    <?php endif; ?>
                </div>

                <div class="">
                    <p>
                        <input type="checkbox"  id= "remember" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> >
                        <label for="remember">Remember Me</label>
                    </p>
                </div>

                <div class="">
                    <button type="submit" class="btn">
                        Login
                    </button>
                    <a class="" href="<?php echo e(route('password.request')); ?>">
                        Forgot Your Password?
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>