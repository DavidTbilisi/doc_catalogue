<?php

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
        Route::get('/users',[\App\Http\Controllers\Administration\AdminController::class, 'list'])->name('users');
        Route::get('/groups',[\App\Http\Controllers\Administration\GroupController::class, 'index'])->name('groups');
        Route::get('/permissions',[\App\Http\Controllers\Administration\PermissionController::class, 'index'])->name('permissions');
    });


    Route::get('/dashboard',[\App\Http\Controllers\Administration\AdminController::class, 'index'])->name('dashboard');

});

require __DIR__.'/auth.php';
