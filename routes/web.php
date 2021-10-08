<?php

use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Administration\GroupController;
use App\Http\Controllers\Administration\PermissionController;
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
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix("admin")->group(function (){
        Route::get('/users/{id?}',[AdminController::class, 'index'])
            ->where('id', "[0-9]+")
            ->name('users');

        Route::get('/groups/{id?}',[GroupController::class, 'index'])
            ->where('id', "[0-9]+")
            ->name('groups');

        Route::get('/permissions/{id?}',[PermissionController::class, 'index'])
            ->where('id', "[0-9]+")
            ->name('permissions');
    });
    Route::get('/dashboard',[AdminController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
