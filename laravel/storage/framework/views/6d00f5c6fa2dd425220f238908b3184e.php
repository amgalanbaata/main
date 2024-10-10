<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <body class="sb-nav-fixed">
        <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <div class="container-fluid px-4">
                    <div class="title d-flex flex-row justify-content-between">
                        <h1 class="mt-4">Хэрэглэгчид</h1>
                        <button class="btn btn-primary mt-4 mb-2">
                            <a href="<?php echo e(route('app-users.create')); ?>" class="btn btn-primary h-5">Хэрэглэгч нэмэх</a>
                        </button>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>имэйл</th>
                                        <th>Нууц үг</th>
                                        <th>дүүрэг</th>
                                        <th>хороо</th>
                                        <th>үүсгэсэн огноо</th>
                                        <th>шинэчлэсэн огноо</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->id); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e($user->password); ?></td>
                                        <td><?php echo e($user->district); ?></td>
                                        <td><?php echo e($user->committee); ?></td>
                                        <td><?php echo e($user->created_at); ?></td>
                                        <td><?php echo e($user->updated_at); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('app-users.edit', $user->id)); ?>" class="btn btn-primary">Засах</a>
                                            <button type="button" class="btn btn-danger" onclick="openConfirmModal('<?php echo e(route('app-users.destroy', $user->id)); ?>')">Устгах</button>
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

    <!-- Modal HTML -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Устгахдаа итгэлтэй байна уу ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Буцах</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">Устгах</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openConfirmModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            myModal.show();
        }
    </script>
</html>
<?php /**PATH E:\TASTAS\SANKYU\ubsoil\laravel\resources\views/admin/appUsers/appUsers.blade.php ENDPATH**/ ?>