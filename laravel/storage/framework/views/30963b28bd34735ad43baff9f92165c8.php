<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <body class="sb-nav-fixed">
        <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">Байршил</h1>
                        <a href="<?php echo e(route('locations.create')); ?>" class="btn btn-lg btn-primary">Цэг нэмэх</a>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-Primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Гарчиг</th>
                                        <th>Тайлбар</th>
                                        <th>Өргөрөг</th>
                                        <th>Уртраг</th>
                                        <th>Өнгө</th>
                                        <th>Үйлдлүүд</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($location->id); ?></td>
                                            <td><?php echo e($location->title); ?></td>
                                            <td><?php echo e($location->comment); ?></td>
                                            <td><?php echo e($location->latitude); ?></td>
                                            <td><?php echo e($location->longitude); ?></td>
                                            <td>
                                                
                                                <?php echo e($location->color); ?>

                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('locations.edit', $location->id)); ?>" class="btn btn-ls btn-Info">Засах</a>
                                                <form action="<?php echo e(route('locations.destroy', $location)); ?>" method="POST" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-ls btn-danger" onclick="return confirm('Та энэ байршлыг устгахдаа итгэлтэй байна уу?')">Устгах</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH E:\TASTAS\SANKYU\ubsoil\laravel\resources\views/admin/user/location.blade.php ENDPATH**/ ?>