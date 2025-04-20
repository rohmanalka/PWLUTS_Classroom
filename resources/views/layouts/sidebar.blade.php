<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset('kaiadmin/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                    class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-item {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">MENU</h4>
                </li>
                <li class="nav-item {{ $activeMenu == 'datamahasiswa' ? 'active' : '' }}">
                    <a href="{{ url('/mahasiswa') }}" class="nav-link">
                        <i class="fas fa-portrait"></i>
                        <p>Data Mahasiswa</p>
                    </a>
                </li>
                <li class="nav-item {{ $activeMenu == 'kelas' ? 'active' : '' }}">
                    <a href="{{ url('/kelas') }}" class="nav-link">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Kelas</p>
                    </a>
                </li>
                <li class="nav-item {{ $activeMenu == 'tugas' ? 'active' : '' }}">
                    <a href="{{ url('/tugas') }}" class="nav-link">
                        <i class="fas fa-book"></i>
                        <p>Tugas</p>
                    </a>
                </li>
                <li class="nav-item {{ $activeMenu == 'tugasmhs' ? 'active' : '' }}">
                    <a href="{{ url('/tugasmhs') }}" class="nav-link">
                        <i class="fas fa-book"></i>
                        <p>Tugas Mahasiswa</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
