<?php

use App\Http\Controllers\DistributorController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
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
    Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
    Route::resource('kategori', KategoriController::class);

    Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
    Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
    Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
    Route::resource('produk', ProdukController::class);

    Route::get('/distributor/data', [DistributorController::class, 'data'])->name('distributor.data');
    Route::resource('distributor', DistributorController::class);

    Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
    Route::resource('pengeluaran', PengeluaranController::class);

    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('pembelian', PembelianController::class)
        ->except('create');

    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::resource('/pembelian_detail', PembelianDetailController::class)
        ->except('create','show','edit');

    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
    Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
    Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
    Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

    Route::get('/transaksi/load-form/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
    Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
    Route::resource('/transaksi', PenjualanDetailController::class)
        ->except('create', 'show', 'edit');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');    
});