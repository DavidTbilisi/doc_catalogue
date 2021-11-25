<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::get("columns/{name?}", function ($table=null) {
    return \App\Models\Io_type::getColumns($table);
})->name('columns');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
