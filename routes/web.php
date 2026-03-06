<?php

use App\Http\Controllers\DistributorController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified',])
    ->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('/kategori', KategoriController::class);

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
    Route::resource('/produk', ProdukController::class);

    Route::get('distributor/data', [DistributorController::class, 'data'])->name('distributor.data');
    Route::resource('/distributor', DistributorController::class);

    Route::get('pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('/pengeluaran', PengeluaranController::class);

    Route::get('/pembelian/{id}/create', PembelianController::class, 'create')->name('pembelian.create');
    Route::resource('/pembelian', PembelianController::class)
        ->except('create');

    Route::resource('/pembelian-detail', PembelianController::class)
        ->except('create','show','edit');
});