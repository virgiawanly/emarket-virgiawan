<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"><img src="{{ asset('img/logo.svg') }}" width="30px" alt=""><span
                    class="ml-1">E-Market</span></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html"><img src="{{ asset('img/logo.svg') }}" width="30px" alt=""></a>
        </div>
        <ul class="sidebar-menu">
            @can('admin')
                <li class="{{ is_active_link('/') }}"><a class="nav-link" href="/"><i
                            class="fas fa-fire"></i><span>Dashboard</span></a></li>
            @endcan

            <!-- Master -->
            @can('edp')
                <li class="menu-header">Master Data</li>
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
                <li class="{{ is_active_link('transaksi/pembelian') }}"><a class="nav-link"
                        href="/transaksi/pembelian"><i class="fas fa-cash-register"></i>
                        <span>Transaksi Pembelian</span></a></li>
                <li class="{{ is_active_link('transaksi/penjualan') }}"><a class="nav-link"
                        href="/transaksi/penjualan"><i class="fas fa-cash-register"></i>
                        <span>Transaksi Penjualan</span></a></li>
            @endcan

            @if (Gate::check('admin') || Gate::check('operator'))
                <!-- Report Area -->
                <li class="menu-header">Laporan</li>
                <li class="{{ is_active_link('laporan/pembelian') }}"><a class="nav-link"
                        href="/laporan/pembelian"><i class="fas fa-download"></i>
                        <span>Laporan Pembelian</span></a></li>
                <li class="{{ is_active_link('laporan/penjualan') }}"><a class="nav-link"
                        href="/laporan/penjualan"><i class="fas fa-upload"></i>
                        <span>Laporan Penjualan</span></a></li>
                <li class="{{ is_active_link('laporan/pendapatan') }}"><a class="nav-link"
                        href="/laporan/pendapatan"><i class="fas fa-chart-bar"></i>
                        <span>Laporan Pendapatan</span></a></li>
            @endif

            @can('admin')
                <!-- Management Area -->
                <li class="menu-header">Management</li>
                <li class="{{ is_active_link('users', 'users/*') }}"><a class="nav-link"
                        href="{{ route('users.index') }}"><i class="fas fa-users"></i>
                        <span>Users</span></a></li>
            @endcan

            <!-- Others -->
            <li class="menu-header">Lainnya</li>
            <li class="{{ is_active_link('profile') }}"><a class="nav-link" href="/profile"><i
                        class="fas fa-user-edit"></i>
                    <span>Edit Profile</span></a></li>
            <li class="{{ is_active_link('tentang-aplikasi') }}"><a class="nav-link"
                    href="/tentang-aplikasi"><i class="fas fa-info"></i>
                    <span>Tentang Aplikasi</span></a></li>
            <li class="mb-4"><a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span></a></li>
        </ul>
    </aside>
</div>
