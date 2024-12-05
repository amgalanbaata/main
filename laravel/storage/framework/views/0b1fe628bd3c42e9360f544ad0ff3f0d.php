<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <body class="sb-nav-fixed">
        <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Хэрэглэгч нэмэх</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="<?php echo e(route('users.store')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <div class="form-group">
                                        <label for="username">Нэвтрэх нэр</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Утас</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Мэйл</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="text" class="form-control" id="password" name="password" required>
                                    </div>

                                    <div class="form-group">
                                        <strong>Төрөл хуваарилах:</strong>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type2" name="type_code" value="2" required>
                                            <label class="form-check-label" for="type2">Хог хягдал</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type3" name="type_code" value="3" required>
                                            <label class="form-check-label" for="type3">Эвдрэл доройтол</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type4" name="type_code" value="4" required>
                                            <label class="form-check-label" for="type4">Бохир</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Хэрэглэгч нэмэх</button>
                                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">Буцах</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(Session::has('message') && Session::get('message') == 'success'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Амжилттай!',
                text: 'Админ үүсгэлт амжилттай боллоо',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo e(route('users.index')); ?>";
                }
            });
        </script>
    <?php elseif(Session::has('message') && Session::get('message') == 'error'): ?>
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
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/user/addUser.blade.php ENDPATH**/ ?>