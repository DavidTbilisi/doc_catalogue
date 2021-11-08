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
})->name("welcome");

Route::middleware(['auth','perms'])->group(function () {
    Route::prefix("admin")->group(function (){

// USER
        Route::get('/users/{id?}',[AdminController::class, 'index'])
            ->where('id', "[0-9]+")
            ->name('users');

        Route::get('/users/add',[AdminController::class, 'create'])
            ->name('adduser');

        Route::post('/users/add',[AdminController::class, 'store'])
            ->name('storeuser');

        Route::get('/users/edit/{id?}',[AdminController::class, 'edit'])
            ->where('id', "[0-9]+")
            ->name('edituser');

        Route::post('/users/edit/{id?}',[AdminController::class, 'update'])
            ->where('id', "[0-9]+")
            ->name('updateuser');



// GROUP
        Route::get('/groups/{id?}',[GroupController::class, 'index'])
            ->where('id', "[0-9]+")
            ->name('groups');

        Route::get('/groups/add',[GroupController::class, 'create'])
            ->name('addgroup');

        Route::post('/groups/add',[GroupController::class, 'store'])
            ->name('storegroup');

        Route::get('/groups/edit/{id?}',[GroupController::class, 'edit'])
            ->where('id', "[0-9]+")
            ->name('editgroup');

        Route::post('/groups/edit/{id?}',[GroupController::class, 'update'])
            ->where('id', "[0-9]+")
            ->name('updategroup');


// PERMISSION
        Route::get('/permissions/{id?}',[PermissionController::class, 'index'])
            ->where('id', "[0-9]+")
            ->name('permissions');

    });
    Route::get('/dashboard',[AdminController::class, 'index'])->name('dashboard');
    Route::get('/profile',[AdminController::class, 'profile'])->name('profile');
});

Route::prefix("test")->group(function (){
Route::get('/login', function () {
   return view("authoverride.login");
});

    Route::get('/register', function () {
        return view("authoverride.register");
    });
});

require __DIR__.'/auth.php';
