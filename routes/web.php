<?php

use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Administration\DocumentsController;
use App\Http\Controllers\Administration\GroupController;
use App\Http\Controllers\Administration\IoTypesController;
use App\Http\Controllers\Administration\OtherTablesController;
use App\Http\Controllers\Administration\PermissionController;
use App\Http\Controllers\Administration\IoController;
use App\Http\Controllers\Administration\SearchController;
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

Route::middleware(['auth', 'perms'])->group(function () {
    Route::prefix("admin")->group(function () {

// USER
        Route::prefix("users")->group(function () {
            Route::name('users.')->group(function () {

                Route::get('/{id?}', [AdminController::class, 'index'])
                    ->where('id', "[0-9]+")
                    ->name('index');

                Route::get('/add', [AdminController::class, 'create'])
                    ->name('add');

                Route::post('/add', [AdminController::class, 'store'])
                    ->name('store');

                Route::get('/edit/{id?}', [AdminController::class, 'edit'])
                    ->where('id', "[0-9]+")
                    ->name('edit');

                Route::post('/edit/{id?}', [AdminController::class, 'update'])
                    ->where('id', "[0-9]+")
                    ->name('update');

                Route::post('/delete/{id?}', [AdminController::class, 'destroy'])
                    ->where('id', "[0-9]+")
                    ->name('delete');

            });
        });

// GROUP
        Route::prefix("groups")->group(function () {
            Route::name('groups.')->group(function () {
                Route::get('/{id?}', [GroupController::class, 'index'])
                    ->where('id', "[0-9]+")
                    ->name('index');

                Route::get('/add', [GroupController::class, 'create'])
                    ->name('add');

                Route::post('/add', [GroupController::class, 'store'])
                    ->name('store');

                Route::get('/edit/{id?}', [GroupController::class, 'edit'])
                    ->where('id', "[0-9]+")
                    ->name('edit');

                Route::post('/edit/{id?}', [GroupController::class, 'update'])
                    ->where('id', "[0-9]+")
                    ->name('update');

                Route::post('/delete/{id?}', [GroupController::class, 'destroy'])
                    ->where('id', "[0-9]+")
                    ->name('delete');
            });
        });


// PERMISSION
        Route::prefix("permissions")->group(function () {
            Route::name('permissions.')->group(function () {
                Route::get('/{id?}', [PermissionController::class, 'index'])
                    ->where('id', "[0-9]+")
                    ->name('index');

                Route::get('/add', [PermissionController::class, 'create'])
                    ->name('add');

                Route::get('/edit/{id?}', [PermissionController::class, 'edit'])
                    ->where('id', "[0-9]+")
                    ->name('edit');

                Route::post('/edit/{id?}', [PermissionController::class, 'update'])
                    ->where('id', "[0-9]+")
                    ->name('update');

                Route::post('/delete/{id?}', [PermissionController::class, 'destroy'])
                    ->where('id', "[0-9]+")
                    ->name('delete');
            });
        });


// IO (information object)
        Route::prefix("io")->group(function () {
            Route::name('io.')->group(function () {

                Route::get('/test/{id?}', [IoController::class, 'test'])
                    ->name('test');

                Route::get('/{id?}', [IoController::class, 'index'])
                    ->where('id', "[0-9]+")
                    ->name('index');

                Route::get('/add', [IoController::class, 'create'])
                    ->name('add');

                Route::post('/add', [IoController::class, 'store'])
                    ->name('store');

                Route::get('/show/{id?}', [IoController::class, 'show'])
                    ->where('id', "[0-9]+")
                    ->name('show');

                Route::get('/edit/{id?}', [IoController::class, 'edit'])
                    ->where('id', "[0-9]+")
                    ->name('edit');

                Route::post('/edit/{id?}', [IoController::class, 'update'])
                    ->where('id', "[0-9]+")
                    ->name('update');

                Route::post('/delete/{id?}', [IoController::class, 'destroy'])
                    ->where('id', "[0-9]+")
                    ->name('delete');

                Route::post('/io_docs/clear/{id?}', [DocumentsController::class, 'clearDocuments'])
                    ->where('id', "[0-9]+")
                    ->name('cleardocs');
            });
        });


// IO (information object)
        Route::prefix("types")->group(function () {
            Route::name('types.')->group(function () {
                Route::get('/{id?}', [IoTypesController::class, 'index'])
                    ->where('id', "[0-9]+")
                    ->name('index');

                Route::get('/add', [IoTypesController::class, 'create'])
                    ->name('add');

                Route::post('/add/{id?}', [IoTypesController::class, 'store'])
                    ->where('id', "[0-9]+")
                    ->name('store');

                Route::get('/show/{id?}', [IoTypesController::class, 'show'])
                    ->name('show');

                Route::get('/edit/{id?}', [IoTypesController::class, 'edit'])
                    ->where('id', "[0-9]+")
                    ->name('edit');

                Route::post('/edit/{id?}', [IoTypesController::class, 'update'])
                    ->where('id', "[0-9]+")
                    ->name('update');

                Route::post('/delete/{id?}', [IoTypesController::class, 'destroy'])
                    ->where('id', "[0-9]+")
                    ->name('delete');

                Route::post('/column', [IoTypesController::class, 'columnChange'])
                    ->name('columnchange');

            });
        });


        Route::prefix("data")->group(function () {
            Route::name('data.')->group(function () {
                Route::get('/{id?}', [OtherTablesController::class, 'index'])
                    ->where('id', "[0-9]+")
                    ->name('index');

                Route::get('/add', [OtherTablesController::class, 'create'])
                    ->name('add');

                Route::post('/add/{id?}', [OtherTablesController::class, 'store'])
                    ->where('id', "[0-9]+")
                    ->name('store');

                Route::get('/show/{table}/{id?}', [OtherTablesController::class, 'show'])
                    ->where('id', "[0-9]+")
                    ->where('table', "[a-z]+")
                    ->name('show');

                Route::get('/edit/{table}/{id?}', [OtherTablesController::class, 'edit'])
                    ->where('id', "[0-9]+")
                    ->where('table', "[a-z]+")
                    ->name('edit');

                Route::post('/edit/{id?}', [OtherTablesController::class, 'update'])
                    ->where('id', "[0-9]+")
                    ->name('update');

                Route::post('/delete/{id?}', [OtherTablesController::class, 'destroy'])
                    ->where('id', "[0-9]+")
                    ->name('delete');

                Route::post('/column', [OtherTablesController::class, 'columnChange'])
                    ->name('columnchange');

            });
        });

        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::post('/search', [SearchController::class, 'search'])->name('searchresults');

    });

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
});

Route::prefix("test")->group(function () {
    Route::get('/login', function () {
        return view("authoverride.login");
    });

    Route::get('/register', function () {
        return view("authoverride.register");
    });
});

require __DIR__ . '/auth.php';
