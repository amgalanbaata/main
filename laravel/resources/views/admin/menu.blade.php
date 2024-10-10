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
                <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="/admin/dashboard">
                    <div class="sb-nav-link-icon"><i class="icon-dashboard"></i></div>
                    Нүүр хуудас
                </a>
                <a class="nav-link {{ Request::is('admin/posts*') ? 'active' : '' }}" href="/admin/posts">
                    <div class="sb-nav-link-icon"><i class="icon-list"></i></div>
                    Санал хүсэлт
                </a>
                @if(Session::get('admin_is') == 0)
                <a class="nav-link {{ Request::is('admin/user*') ? 'active' : '' }}" href="/admin/user">
                    <div class="sb-nav-link-icon"><i class="icon-user"></i></div>
                    Хэрэглэгчид
                </a>
                @endif
                <a class="nav-link {{ Request::is('admin/report*') ? 'active' : '' }}" href="/admin/report">
                    <div class="sb-nav-link-icon"><i class="icon-file"></i></div>
                    Тайлан
                </a>
                @if(Session::get('admin_is') == 0)
                <a class="nav-link {{ Request::is('admin/location/*') ? 'active' : '' }}" href="/admin/location">
                    <div class="sb-nav-link-icon"><i class="icon-list"></i></div>
                    Байршил
                </a>
                @endif
                @if(Session::get('admin_is') == 0)
                <a class="nav-link {{ Request::is('admin/app-user') ? 'active' : '' }}" href="/admin/app-user">
                    <div class="sb-nav-link-icon"><i class="icon-list"></i></div>
                    Апп Хэрэглэгчид
                </a>
                @endif
            </div>
        </div>
    </nav>
</div>
