<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\PermintaanBarangController;
use App\Http\Controllers\PengajuanPoController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\RiwayatHargaController;
use App\Http\Controllers\StokBarangController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get(
    '/home',
    [App\Http\Controllers\HomeController::class, 'index']
)->name('home');

Route::middleware(['auth'])->group(function () {

    //user
    Route::resource('user', UserController::class);

    Route::patch(
        '/user/{user}/setujui',
        [UserController::class, 'setujui']
    )->name('user.setujui');

    Route::patch(
        '/user/{user}/tolak',
        [UserController::class, 'tolak']
    )->name('user.tolak');

    //kategori barang
    Route::resource(
        'kategori-barang',
        KategoriBarangController::class
    );

    //barang
    Route::resource(
        'barang',
        BarangController::class
    );

    Route::resource('permintaan-barang', PermintaanBarangController::class);

    Route::post(
    '/permintaan-barang/{permintaan}/penuhi',
    [PermintaanBarangController::class, 'penuhi']
)->name('permintaan-barang.penuhi');

    Route::resource('barang-keluar', BarangKeluarController::class);

    Route::get('pengajuan-po/create/{permintaan?}', [PengajuanPoController::class, 'create'])
    ->name('pengajuan-po.create');

    Route::resource('pengajuan-po', PengajuanPoController::class);
    Route::get('pengajuan-po/{pengajuanPo}/approval', [PengajuanPoController::class, 'approval'])
        ->name('pengajuan-po.approval');
    Route::post('pengajuan-po/{pengajuanPo}/approval', [PengajuanPoController::class, 'simpanApproval'])
        ->name('pengajuan-po.simpan-approval');

    Route::resource('barang-masuk', BarangMasukController::class);

    Route::resource('stok-opname', StokOpnameController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::get('riwayat-harga', [RiwayatHargaController::class, 'index'])
        ->name('riwayat-harga.index');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/menu', function () {return view('laporan.menu');})->name('menu');
        Route::get('barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
        Route::get('barang-masuk',  [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('stok-barang',   [LaporanController::class, 'stokBarang'])->name('stok-barang');
        Route::get('pengajuan-po',  [LaporanController::class, 'pengajuanPo'])->name('pengajuan-po');
        Route::get('riwayat-harga', [LaporanController::class, 'riwayatHarga'])->name('riwayat-harga');
    });

    // //barang masuk
    // Route::resource(
    //     'barang-masuk',
    //     BarangMasukController::class
    // );

    // Route::get(
    //     '/barang-masuk/po/{id}',
    //     [BarangMasukController::class, 'getPoDetail']
    // )->name('barang-masuk.po-detail');

    // //barang keluar
    // Route::resource(
    //     'barang-keluar',
    //     BarangKeluarController::class
    // );

    // //permintaan barang
    // Route::resource(
    //     'permintaan-barang',
    //     PermintaanBarangController::class
    // );

    // //pengajuan po
    // Route::resource(
    //     'pengajuan-po',
    //     PengajuanPoController::class
    // );

    // Route::post(
    //     '/pengajuan-po/{pengajuanPo}/approve',
    //     [PengajuanPoController::class, 'approve']
    // )->name('pengajuan-po.approve');

    // //stok opname
    // Route::resource(
    //     'stok-opname',
    //     StokOpnameController::class
    // );

    // //laporan
    // Route::get(
    //     '/laporan',
    //     [LaporanController::class, 'index']
    // )->name('laporan.index');



    Route::resource('kategori-barang', KategoriBarangController::class)->except(['show']);

    Route::get('/stok-barang', [BarangController::class, 'stokBarang'])
        ->name('stok-barang.index');

});