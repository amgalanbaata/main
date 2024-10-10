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
                            <h2>Нэмэх</h2>
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

                            <form action="<?php echo e(route('locations.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Гарчиг</label>
                                    <input type="text" name="title" id="title" class="form-control" value="<?php echo e(old('title')); ?>" required>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="comment" class="form-label">Тайлбар</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3"><?php echo e(old('comment')); ?></textarea>
                                    <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude" class="form-label">Өргөрөг</label>
                                            <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo e(old('latitude')); ?>" required>
                                            <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude" class="form-label">Уртраг</label>
                                            <input type="text" name="longitude" id="longitude" class="form-control" value="<?php echo e(old('longitude')); ?>" required>
                                            <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="text-danger"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Өнгө</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorYellow" value="yellow" <?php echo e(old('color') == 'yellow' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="colorYellow">Шар</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorBlue" value="blue" <?php echo e(old('color') == 'blue' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="colorBlue">Цэнхэр</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorBlack" value="black" <?php echo e(old('color') == 'black' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="colorBlack">Хар</label>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Нэмэх</button>
                                    <a href="<?php echo e(route('locations.index')); ?>" class="btn btn-outline-secondary">Цуцлах</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH E:\TASTAS\SANKYU\ubsoil\laravel\resources\views/admin/user/addLocation.blade.php ENDPATH**/ ?>