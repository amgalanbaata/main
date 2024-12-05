<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
<!-- Navbar Brand-->
<a class="navbar-brand ps-3 bg-primary" href="/admin/dashboard"><img src="<?php echo e(asset('images/NBOG-logo.png')); ?>" width="150" alt=""></a>
<!-- Sidebar Toggle-->
<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="icon-reorder"></i></button>
<!-- Navbar Search-->
<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
<!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-user"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <?php if(Session::get('admin_is') != 0): ?>
                <li><a class="dropdown-item" href="/admin/user/profile">Профайл</a></li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="/admin/logout">Гарах</a></li>
            </ul>
            </ul>
        </li>
    </ul>
</form>
</nav>

<script>
    window.addEventListener('DOMContentLoaded', event => {
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            });
        }
    });
</script>
<?php /**PATH C:\Users\Amka\Documents\ubsoil\laravel\resources\views/admin/header.blade.php ENDPATH**/ ?>