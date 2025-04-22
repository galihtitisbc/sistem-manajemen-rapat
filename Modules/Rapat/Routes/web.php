<?php

use Illuminate\Support\Facades\Route;
use Modules\Rapat\Http\Controllers\RapatController;
use Modules\Rapat\Http\Controllers\RapatDashboardController;
use Modules\Rapat\Http\Controllers\TindakLanjutRapatController;

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

Route::group(['middleware' => ['auth', 'permission']], function () {
    Route::prefix('rapat')->group(function () {
        Route::get('/dashboard', [RapatDashboardController::class, 'index']);
        Route::prefix('agenda-rapat')->group(function () {
            Route::get('/', [RapatController::class, 'index']);
            Route::get('/create', [RapatController::class, 'create']);
            Route::post('/store', [RapatController::class, 'store']);
            Route::get('/{rapatAgenda:slug}/detail', [RapatController::class, 'show']);
            Route::get('/{file}/download', [RapatController::class, 'downloadLampiran']);
            Route::middleware(['pimpinanRapat'])->group(function () {
                Route::get('/{rapatAgenda:slug}/edit', [RapatController::class, 'edit']);
                Route::get('/{rapatAgenda:slug}/batal', [RapatController::class, 'ubahStatusRapat']);
            });
            Route::get('/{rapatAgenda:slug}/tugas', [TindakLanjutRapatController::class, 'isiPenugasan']);
            Route::get('/{rapatAgenda:slug}/tugaskan/{user}', [TindakLanjutRapatController::class, 'tugaskanPesertaRapat']);
            Route::post('/{rapatAgenda:slug}/tugaskan/{user}', [TindakLanjutRapatController::class, 'createTugasPesertaRapat']);
        });
        Route::prefix('tindak-lanjut-rapat')->group(function () {
            Route::get('/', [TindakLanjutRapatController::class, 'index']);
            Route::get('/{rapatAgenda:slug}/detail', [TindakLanjutRapatController::class, 'show']);
            Route::get('/{rapatTindakLanjut:slug}/detail/tugas', [TindakLanjutRapatController::class, 'detailTugas']);
            Route::get('/tugas/{rapatTindakLanjut:slug}/unggah-tugas', [TindakLanjutRapatController::class, 'showUploadTugas']);
            Route::post('/tugas/{rapatTindakLanjut:slug}/unggah-tugas', [TindakLanjutRapatController::class, 'uploadTugas']);
            Route::get('/tugas/{rapatTindakLanjut:slug}/ubah-tugas', [TindakLanjutRapatController::class, 'showEditTugas']);
            Route::put('/tugas/{rapatTindakLanjut:slug}/ubah-tugas', [TindakLanjutRapatController::class, 'editTugas']);
            Route::post('/{rapatTindakLanjut:slug}/detail/simpan-tugas', [TindakLanjutRapatController::class, 'simpanTugas']);
        });
    });
});
