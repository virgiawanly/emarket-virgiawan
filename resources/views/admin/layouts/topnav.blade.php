<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" id="sidebarToggler" class="nav-link nav-link-lg"><i
                        class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a>
            </li>
        </ul>
    </form>

    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                @auth
                    <img alt="image" src="{{ Auth::user()->get_photo() }}"
                        class="rounded-circle mr-1" style="width:30px;height: 30px;object-fit: cover">
                    <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
                @endauth
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Login 5 menit lalu</div>
                <a href="/profile" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profil Saya
                </a>
                <a href="features-settings.html" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
