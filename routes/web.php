<?php

use App\Http\Controllers\Api\KapalSearchController;
use App\Http\Controllers\Api\NakhodaSearchController;
use App\Http\Controllers\Api\PelabuhanSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Master\BarangB3Controller;
use App\Http\Controllers\Master\JenisKapalController;
use App\Http\Controllers\Master\JenisPelayaranController;
use App\Http\Controllers\Master\KapalController;
use App\Http\Controllers\Master\NakhodaController;
use App\Http\Controllers\Master\PelabuhanController;
use App\Http\Controllers\Master\TipePelabuhanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kunjungan Routes (Main Feature)
    Route::resource('kunjungan', KunjunganController::class);

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/pelra', [LaporanController::class, 'pelra'])->name('pelra');
        Route::get('/perintis', [LaporanController::class, 'perintis'])->name('perintis');
        Route::get('/ferry', [LaporanController::class, 'ferry'])->name('ferry');
        Route::get('/dalam-negeri', [LaporanController::class, 'dalamNegeri'])->name('dalam-negeri');
        Route::get('/luar-negeri', [LaporanController::class, 'luarNegeri'])->name('luar-negeri');
        Route::get('/rekap-spb', [LaporanController::class, 'rekapSpb'])->name('rekap-spb');
        Route::get('/rekap-operasional', [LaporanController::class, 'rekapOperasional'])->name('rekap-operasional');
        Route::get('/export-excel', [LaporanController::class, 'exportDataDukung'])->name('export-excel');
    });

    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/excel', [ImportExcelController::class, 'index'])->name('excel.index');
        Route::post('/excel', [ImportExcelController::class, 'store'])->name('excel.store');
    });

    // Master Data Routes
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('kapal', KapalController::class);
        Route::post('kapal/store-jenis-kapal', [KapalController::class, 'storeJenisKapal'])->name('kapal.store-jenis-kapal');
        Route::post('kapal/store-bendera', [KapalController::class, 'storeBendera'])->name('kapal.store-bendera');
        Route::resource('jenis-kapal', JenisKapalController::class);
        Route::resource('tipe-pelabuhan', TipePelabuhanController::class)->except(['create', 'edit', 'show']);
        Route::resource('pelabuhan', PelabuhanController::class);
        Route::resource('nakhoda', NakhodaController::class);
        Route::resource('barang-b3', BarangB3Controller::class);
        Route::resource('jenis-pelayaran', JenisPelayaranController::class)->except(['create', 'edit', 'show']);
    });

    // API Routes for Autocomplete
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('kapal/search', [KapalSearchController::class, 'search'])->name('kapal.search');
        Route::get('pelabuhan/search', [PelabuhanSearchController::class, 'search'])->name('pelabuhan.search');
        Route::get('pelabuhan/internal', [PelabuhanSearchController::class, 'internal'])->name('pelabuhan.internal');
        Route::get('nakhoda/search', [NakhodaSearchController::class, 'search'])->name('nakhoda.search');
    });
});

require __DIR__.'/auth.php';
