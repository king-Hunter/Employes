<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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



Auth::routes();

Route::group(['middleware' => ['web', 'auth']], function () {
    
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/add', [HomeController::class, 'getAddNew'])->name('getAdd');
    Route::post('/add', [HomeController::class, 'postAddNew'])->name('postAdd');
    Route::post('/delete', [HomeController::class, 'deleteEmploye'])->name('delete');
    Route::post('/status', [HomeController::class, 'statusEmploye'])->name('status');
    Route::post('/view', [HomeController::class, 'viewEmploye'])->name('view');
    Route::get('/edit/{id}', [HomeController::class, 'getEdit'])->name('getEditE');
    Route::post('/edite/{id}', [HomeController::class, 'postEdit'])->name('postEditE');
});


