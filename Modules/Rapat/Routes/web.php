<?php

use Illuminate\Support\Facades\Route;
use Modules\Rapat\Http\Controllers\RapatController;

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
        Route::get('/dashboard', [RapatController::class, 'index']);
    });
});
