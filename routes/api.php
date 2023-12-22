<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::middleware(['throttle'])->group(function () {

    //USER Login
    Route::post('/login', 'API\LoginController@login');

});

Route::middleware(['blog.auth'])->group( function () {
    Route::get('/blogList', 'API\BlogController@index');
    Route::post('/blogstore', 'API\BlogController@store');
    Route::post('/togglelike/{id?}', 'API\BlogController@togglelike');
});

/*Route::middleware(['auth:sanctum','blog.auth'])->group( function () {
    Route::post('/togglelike/{id?}', 'API\BlogController@togglelike');
    Route::get('/blogList', 'API\BlogController@index');
});*/
