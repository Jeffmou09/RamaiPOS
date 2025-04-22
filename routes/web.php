<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanTransaksiController;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/emailcheck', [AuthController::class, 'showEmailForm'])->name('emailcheck');
Route::post('send-credentials', [AuthController::class, 'sendCredentials'])->name('send.credentials');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/get-income-data', [DashboardController::class, 'getIncomeData']);
    Route::get('/dashboard/get-filtered-data', [DashboardController::class, 'getFilteredData']);

    Route::get('/inputstok', [StokProdukController::class, 'index'])->name('inputstok');
    Route::post('/inputstok/store', [StokProdukController::class, 'store'])->name('inputstok.store');
    Route::get('/stok/detail/{id}', [StokProdukController::class, 'detail'])->name('produk.stokdetail');

    Route::get('/daftarproduk', [ProdukController::class, 'index'])->name('daftarproduk');
    Route::post('/daftarproduk/store', [ProdukController::class, 'store'])->name('daftarproduk.store');
    Route::get('/daftarproduk/create', [ProdukController::class, 'create'])->name('produk.tambahproduk');
    Route::get('/daftarproduk/{id}/edit', [ProdukController::class, 'edit'])->name('daftarproduk.editproduk');
    Route::put('/daftarproduk/{id}', [ProdukController::class, 'update'])->name('daftarproduk.update');
    Route::delete('/daftarproduk/{id}', [ProdukController::class, 'destroy'])->name('daftarproduk.destroy');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.tambahcustomer');
    Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.editcustomer');
    Route::post('/customer/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/cetak/{id}', [TransaksiController::class, 'cetakNota'])->name('transaksi.cetak');
    Route::get('/transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');
    Route::get('/transaksi/detail/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    Route::get('/laporan/transaksi/pdf', [LaporanTransaksiController::class, 'generateTransaksiPDF'])->name('laporan.transaksi.pdf');
    Route::get('/laporan/produk/pdf', [LaporanTransaksiController::class, 'generateProdukPDF'])->name('laporan.produk.pdf');
    Route::get('/laporan/transaksi', [LaporanTransaksiController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('/laporan/produk', [LaporanTransaksiController::class, 'produk'])->name('laporan.produk');

    Route::get('/laporan', function () {
        return view('laporan.laporan'); 
    })->name('laporan');
});