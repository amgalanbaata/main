<div id="layoutSidenav_nav">
    <style>
        .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .nav-link.active .sb-nav-link-icon {
            color: #fff;
        }
    </style>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link <?php echo e(Request::is('admin/dashboard*') ? 'active' : ''); ?>" href="/admin/dashboard">
                    <div class="sb-nav-link-icon"><i class="icon-dashboard"></i></div>
                    Нүүр хуудас
                </a>
                <a class="nav-link <?php echo e(Request::is('admin/post*') || Request::is('admin/addpost*') ? 'active' : ''); ?>" href="/admin/posts">
                    <div class="sb-nav-link-icon"><i class="icon-list"></i></div>
                    Мэдэгдлүүд
                </a>
                <?php if(Session::get('admin_is') == 0): ?>
                <a class="nav-link <?php echo e(Request::is('admin/user*') ? 'active' : ''); ?>" href="/admin/user">
                    <div class="sb-nav-link-icon"><i class="icon-user"></i></div>
                    НБОГ хэрэглэгч
                </a>
                <?php endif; ?>
                <a class="nav-link <?php echo e(Request::is('admin/report*') ? 'active' : ''); ?>" href="/admin/report">
                    <div class="sb-nav-link-icon"><i class="icon-file"></i></div>
                    Статистик
                </a>
                
                <a class="nav-link <?php echo e(Request::is('admin/location*') || Request::is('locations*') ? 'active' : ''); ?>" href="/admin/location">
                    <div class="sb-nav-link-icon"><i class="icon-list"></i></div>
                    Байршил
                </a>
                
                <?php if(Session::get('admin_is') == 0): ?>
                <a class="nav-link <?php echo e(Request::is('admin/app-user*') ? 'active' : ''); ?>" href="/admin/app-user">
                    <div class="sb-nav-link-icon"><i class="icon-user"></i></div>
                    Апп Хэрэглэгчид
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/menu.blade.php ENDPATH**/ ?>