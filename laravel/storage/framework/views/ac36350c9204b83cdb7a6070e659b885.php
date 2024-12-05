<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <body class="sb-nav-fixed">
        <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <div class="container mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2>Засварлах</h2>
                            <a href="<?php echo e(route('locations.index')); ?>" class="btn btn-outline-secondary">Буцах</a>
                        </div>
                        <div class="card-body">
                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('locations.update', $location->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Гарчиг</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo e($location->title); ?>" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="comment" class="form-label">Тайлбар</label>
                                    <textarea name="comment" class="form-control" rows="3"><?php echo e($location->comment); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude" class="form-label">Өргөрөг</label>
                                            <input type="text" name="latitude" class="form-control" value="<?php echo e($location->latitude); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude" class="form-label">Уртраг</label>
                                            <input type="text" name="longitude" class="form-control" value="<?php echo e($location->longitude); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Бохирдлын түвшин</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorRed" value="red" <?php echo e($location->color == 'red' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="colorRed">Их</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorYellow" value="yellow" <?php echo e($location->color == 'yellow' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="colorYellow">Дунд</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorGreen" value="green" <?php echo e($location->color == 'green' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="colorGreen">Бага</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Шинэчлэх</button>
                                    <a href="<?php echo e(route('locations.index')); ?>" class="btn btn-outline-secondary">Буцах</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/user/locationEdit.blade.php ENDPATH**/ ?>