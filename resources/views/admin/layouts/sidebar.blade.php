<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"><img src="{{ asset('img/logo.svg') }}" width="30px" alt=""><span class="ml-1">E-Market</span></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html"><img src="{{ asset('img/logo.svg') }}" width="30px" alt=""></a>
        </div>
        <ul class="sidebar-menu">
            @can('admin')
                <!-- Dashboard -->
                <li class="menu-header">Menu</li>
                <li class="{{ is_active_link('/') }}"><a class="nav-link" href="/"><i
                            class="fas fa-fire"></i><span>Dashboard</span></a></li>
            @endcan

            <!-- Master -->
            @can('edp')
                <li class="menu-header">Master</li>
                <li class="{{ is_active_link('produk', 'produk/*') }}"><a class="nav-link" href="/produk"><i
                            class="fas fa-box-open"></i>
                        <span>Produk</span></a></li>
                <li class="{{ is_active_link('barang', 'barang/*') }}"><a class="nav-link" href="/barang"><i
                            class="fas fa-boxes"></i>
                        <span>Barang</span></a></li>
                <li class="{{ is_active_link('pelanggan', 'pelanggan/*') }}"><a class="nav-link"
                        href="/pelanggan"><i class="fas fa-user"></i>
                        <span>Pelanggan</span></a></li>
                <li class="{{ is_active_link('pemasok', 'pemasok/*') }}"><a class="nav-link" href="/pemasok"><i
                            class="fas fa-truck"></i>
                        <span>Pemasok</span></a></li>
            @endcan

            @can('operator')
                <!-- Transaction Area -->
                <li class="menu-header">Transaksi</li>
                <li class="{{ is_active_link('pembelian', 'pembelian/*') }}"><a class="nav-link"
                        href="/pembelian"><i class="fas fa-download"></i>
                        <span>Pembelian / Stock In</span></a></li>
                <li class="{{ is_active_link('penjualan', 'penjualan/*') }}"><a class="nav-link"
                        href="/penjualan"><i class="fas fa-upload"></i>
                        <span>Penjualan / Stock Out</span></a></li>
                <li class="{{ is_active_link('transaksi', 'transaksi/*') }}"><a class="nav-link"
                        href="/transaksi"><i class="fas fa-cash-register"></i>
                        <span>Transaksi Baru</span></a></li>
            @endcan

            @if (Gate::check('admin') || Gate::check('operator'))
                <li class="{{ is_active_link('laporan/pendapatan') }}"><a class="nav-link"
                        href="/laporan/pendapatan"><i class="fas fa-chart-bar"></i>
                        <span>Laporan Pendapatan</span></a></li>
            @endif

            @can('admin')
                <li class="{{ is_active_link('users', 'users/*') }}"><a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i>
                        <span>Users</span></a></li>
            @endcan

            <!-- Others -->
            <li class="menu-header">Lainnya</li>
            @can('admin')
                <li><a class="nav-link" href="/"><i class="fas fa-cog"></i>
                        <span>Pengaturan</span></a></li>
            @endcan
            <li class="{{ is_active_link('tentang-aplikasi') }}"><a class="nav-link"
                    href="/tentang-aplikasi"><i class="fas fa-info"></i>
                    <span>Tentang Aplikasi</span></a></li>
            <li class="mb-4"><a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span></a></li>
        </ul>
    </aside>
</div>
