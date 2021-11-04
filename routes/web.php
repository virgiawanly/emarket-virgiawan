<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'forgot_password']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::middleware('level:2')->group(function () {
        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::resource('/produk', ProdukController::class);

        Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
        Route::put('/barang/update-tarik-barang/{barang}', [BarangController::class, 'updateTarikBarang'])->name('barang.tarikBarang');
        Route::resource('/barang', BarangController::class);

        Route::get('/pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
        Route::resource('/pelanggan', PelangganController::class);

        Route::get('/pemasok/data', [PemasokController::class, 'data'])->name('pemasok.data');
        Route::resource('/pemasok', PemasokController::class);
    });

    Route::middleware('level:1,3')->group(function () {
        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/detail/data/{id}', [PembelianController::class, 'detail_data'])->name('pembelian.detail');

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan/detail/data/{id}', [PenjualanController::class, 'detail_data'])->name('penjualan.detail');
    });

    Route::middleware('level:3')->group(function(){
        Route::get('/transaksi/pembelian', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
        Route::get('/transaksi/penjualan', [PenjualanController::class, 'create'])->name('penjualan.create');
        Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('/transaksi/cetak_struk', [PenjualanController::class, 'cetak_struk'])->name('penjualan.cetak_struk');
    });

    Route::middleware('level:1,3')->group(function () {
        Route::get('/laporan/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('/laporan/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/laporan/pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
        Route::get('/laporan/pendapatan/export/pdf/{tgl_awal}/{tgl_akhir}', [LaporanController::class, 'exportPendapatanPDF'])->name('laporan.pdf_pendapatan');
        Route::get('/laporan/pendapatan/data/{tgl_awal}/{tgl_akhir}', [LaporanController::class, 'dataPendapatan'])->name('laporan.data_pendapatan');
    });

    Route::middleware('level:1')->group(function () {
        Route::get('/users/data', [UserController::class, 'all_user'])->name('user.all_user');
        Route::resource('/users', UserController::class)->except('create', 'edit');
    });

    Route::get('/tentang-aplikasi', function () {
        return view('admin.tentang-aplikasi');
    });

    Route::get('/profile', [UserController::class, 'showProfile']);
    Route::post('/profile', [UserController::class, 'updateProfile']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
