<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <body class="sb-nav-fixed">
        <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <style>
                    .form-group {
                        margin-top: 10px;
                    }
                </style>
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">АПП хэрэглэгч засах</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="<?php echo e(route('app-users.update', $user->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="email">Имэйл</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo e($user->email); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="password" class="form-control" id="password" name="password" value="<?php echo e($user->password); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="district">Дүүрэг</label>
                                        <input type="district" class="form-control" id="district" name="district" value="<?php echo e($user->district); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="committee">Хороо</label>
                                        <input type="committee" class="form-control" id="committee" name="committee" value="<?php echo e($user->committee); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">Шинэчлэх</button>
                                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary mt-4">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/appUsers/appUserEdit.blade.php ENDPATH**/ ?>