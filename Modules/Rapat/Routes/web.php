<?php

use Illuminate\Support\Facades\Route;
use Modules\Rapat\Http\Controllers\RapatController;
use Modules\Rapat\Http\Controllers\RapatDashboardController;

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
        Route::get('/agenda-rapat', [RapatController::class, 'index']);
        Route::get('/agenda-rapat/create', [RapatController::class, 'create']);
        Route::post('/agenda-rapat/store', [RapatController::class, 'store']);
    });
});
