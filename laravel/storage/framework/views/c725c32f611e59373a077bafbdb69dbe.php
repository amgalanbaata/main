<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <body class="sb-nav-fixed">
        <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Хэрэглэгчийг засах</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="username">Нэвтрэх нэр</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo e($user->username); ?>" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="phone">Утас</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e($user->phone); ?>" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="email">Мэйл</label>
                                        <input type="text" class="form-control" id="email" name="email" value="<?php echo e($user->email); ?>" required>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="password">Нууц үг</label>
                                        <input type="password" class="form-control" id="password" name="password" value="<?php echo e($user->password); ?>" required>
                                    </div>
                                    <?php if($user->type_code != 0): ?>

                                    <div class="form-group">
                                        <strong class="typeS">Төрөл хуваарилах:</strong>

                                        <div class="form-grout form-group-inline">
                                            <input class="form-check-input" type="radio" id="type2" name="type_code" value="2" <?php echo e($user->type_code == 2 ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="type2">Хог хягдал</label>
                                        </div>

                                        <div class="form-grout form-group-inline">
                                            <input class="form-check-input" type="radio" id="type3" name="type_code" value="3" <?php echo e($user->type_code == 3 ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="type3">Эвдрэл доройтол</label>
                                        </div>

                                        <div class="form-grout form-group-inline">
                                            <input class="form-check-input" type="radio" id="type4" name="type_code" value="4" <?php echo e($user->type_code == 4 ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="type4">Бохир</label>
                                        </div>
                                        <br>
                                    </div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-primary mt-3">Шинэчлэх</button>
                                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary mt-3">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(Session::has('message') && Session::get('message') == 'successUserEdit'): ?>
    <p><?php echo e(Session::get('message')); ?></p>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Амжилттай!',
            text: 'Шинэчлэлт амжилттай боллоо',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo e(route('users.index')); ?>";
            }
        });
    </script>
    <?php elseif(Session::has('message') && Session::get('message') == 'errorUserEdit'): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Алдаа!',
            text: 'Шинэчлэх явцад алдаа гарлаа.',
            confirmButtonText: 'OK'
        });
    </script>
    <?php endif; ?>
</html>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/user/userEdit.blade.php ENDPATH**/ ?>