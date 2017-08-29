<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row">
        <div class="col s8 offset-s2">

            <form class="form-horizontal" method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo e(csrf_field()); ?>

                    <div class="">
                            <label for="name" class="">Name</label>
                            <input id="name" type="text" class="" name="name" value="<?php echo e(old('name')); ?>" required autofocus>
                            <?php if($errors->has('name')): ?>
                                <span class="">
                                    <strong><?php echo e($errors->first('name')); ?></strong>
                                </span>
                            <?php endif; ?>
                    </div>

                    <div class="">
                        <label for="email" class="">E-Mail Address</label>
                        <input id="email" type="email" class="" name="email" value="<?php echo e(old('email')); ?>" required>
                        <?php if($errors->has('email')): ?>
                            <span class="">
                                <strong><?php echo e($errors->first('email')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="">
                        <label for="password" class="">Password</label>
                        <input id="password" type="password" class="" name="password" required>
                        <?php if($errors->has('password')): ?>
                            <span class="">
                                <strong><?php echo e($errors->first('password')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="">
                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="">
                        <label for="date" class="">Date Of Birth</label>
                        <input id="date" type="date" class="" name="date" required>
                        <?php if($errors->has('date')): ?>
                            <span class="">
                                <strong><?php echo e($errors->first('date')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="">
                        <label for="country" class="">Country</label>
                        <input id="country" type="text" class="" name="country" value="<?php echo e(old('country')); ?>" required>
                        <?php if($errors->has('country')): ?>
                            <span class="">
                                <strong><?php echo e($errors->first('country')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="">
                        <label for="phone" class="">Phone</label>
                        <input id="phone" type="text" class="" name="phone" value="<?php echo e(old('phone')); ?>" required>
                        <?php if($errors->has('phone')): ?>
                            <span class="">
                                <strong><?php echo e($errors->first('phone')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="">
                        <button type="submit" class="btn">
                                    Register
                        </button>
                    </div>
            </form>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>