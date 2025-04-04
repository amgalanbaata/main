<?php echo $__env->make('admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<html>
    <style>
        .addButton {
            text-decoration: none;
        }
        .phone {
            background-color: blue;
        }
        #datatablesSimple tbody tr td:nth-child(2) {
            color: royalblue !important;
        }
    </style>
    <body class="sb-nav-fixed">
    <?php echo $__env->make('admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="layoutSidenav">
            <?php echo $__env->make('admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="title d-flex flex-row justify-content-between">
                            <h1 class="mt-4">Мэдэгдлүүд</h1>
                            <button class="addPost btn btn-primary mt-4 mb-2"><a class="addButton text-white" href="/admin/addpost">Мэдэгдэл нэмэх</a></button>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check1" value="true" name="check1"
                                            <?php if($condition['check1']): ?> checked <?php endif; ?>
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check1">Шинээр ирсэн
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check2" value="true" name="check2"
                                            <?php if($condition['check2']): ?> checked <?php endif; ?>
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check2">Давхардсан</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check3" value="true" name="check3"
                                            <?php if($condition['check3']): ?> checked <?php endif; ?>
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check3">Нэмэлт мэдээлэл шаардлагатай</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check4" value="true" name="check4"
                                            <?php if($condition['check4']): ?> checked <?php endif; ?>
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check4">Татгалзсан</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check5" value="true" name="check5"
                                            <?php if($condition['check5']): ?> checked <?php endif; ?>
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check5">Хөрсний шинжилгээ хийх</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check6" value="true" name="check6"
                                            <?php if($condition['check6']): ?> checked <?php endif; ?>
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check6">Байршилд шууд бүртгэх</label>
                                    </div>
                                </form>
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Нэр</th>
                                            <th>Утас</th>
                                            <th>Сэтгэгдэл</th>
                                            <th>Статус</th>
                                            <th>Төрөл</th>
                                            <th>Зөвшөөрсөн</th>
                                            <th>Огноо</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Нэр</th>
                                            <th>Утас</th>
                                            <th>Сэтгэгдэл</th>
                                            <th>Статус</th>
                                            <th>Төрөл</th>
                                            <th>Зөвшөөрсөн</th>
                                            <th>Огноо</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($data->name); ?></td>
                                            <td><?php echo e($data->number); ?></td>
                                            <td>
                                                <?php if(strlen($data->comment) > 20): ?>
                                                    <span class="comment-text" data-toggle="tooltip" title="<?php echo e($data->comment); ?>">
                                                        <?php echo e(substr($data->comment, 0, 20)); ?>...
                                                    </span>
                                                <?php else: ?>
                                                <?php echo e($data->comment); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php switch($data->status):
                                                    case (1): ?>
                                                        <i class="fas fa-inbox text-primary"></i> <?php echo e($data->status_name); ?>

                                                        <?php break; ?>
                                                    <?php case (2): ?>
                                                        <i class="fas fa-envelope-open-text text-warning"></i> <?php echo e($data->status_name); ?>

                                                        <?php break; ?>
                                                    <?php case (3): ?>
                                                        <i class="fas fa-check-circle text-success"></i> <?php echo e($data->status_name); ?>

                                                    <?php break; ?>
                                                    <?php case (4): ?>
                                                        <i class="fas fa-times-circle text-danger"></i> <?php echo e($data->status_name); ?>

                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <?php echo e($data->status_name); ?>

                                                <?php endswitch; ?>
                                            </td>
                                            <td><?php echo e($data->type_name); ?></td>
                                            <td><?php echo e($data->agreed); ?></td>
                                            <td><?php echo e($data->created_at); ?></td>
                                            <td>
                                                <a type="button" class="btn btn-link" href="https://www.google.com/maps/search/?api=1&query=<?php echo e($data->latitude); ?>, <?php echo e($data->longitude); ?>" target="blank">
                                                    <button class="btn btn-info text-white">Байршил</button>
                                                </a>
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-link" href="/admin/post?id=<?php echo e($data->id); ?>">
                                                    <button class="btn btn-primary">Дэлгэрэнгүй</button>
                                                </a>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple, {
                    labels: {
                        placeholder: "Хайх...",
                        perPage: "Хуудас",
                        noRows: "Өгөгдөл алга.",
                        info: "Нийт {rows}-с {start}-ээс {end}-ийг харуулж байна.",
                        noResults: "Таны хайлтын асуулгад тохирох илэрц олдсонгүй.",
                    }
                });
            }
        });
    </script>
</html>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/posts.blade.php ENDPATH**/ ?>