<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
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

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
Route::resource('/produk', ProdukController::class);

Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
Route::resource('/barang', BarangController::class);

Route::get('/pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
Route::resource('/pelanggan', PelangganController::class);

Route::get('/pemasok/data', [PemasokController::class, 'data'])->name('pemasok.data');
Route::resource('/pemasok', PemasokController::class);

Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
Route::get('/pembelian/detail/data/{id}', [PembelianController::class, 'detail_data'])->name('pembelian.detail');
Route::get('/pembelian/transaksi-baru', [PembelianController::class, 'create'])->name('pembelian.create');
Route::resource('/pembelian', PembelianController::class)->except('create', 'edit');

Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
Route::get('/penjualan/detail/data/{id}', [PenjualanController::class, 'detail_data'])->name('penjualan.detail');
Route::resource('/penjualan', PenjualanController::class)->except('create', 'edit');

Route::get('/transaksi', [PenjualanController::class, 'create'])->name('penjualan.create');

Route::get('/tentang-aplikasi', function(){
    return view('admin.tentang-aplikasi');
});
