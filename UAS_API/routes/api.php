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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::post('verify', 'Api\AuthController@verify');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('book', 'Api\BookController@index');
    Route::get('book/{id}', 'Api\BookController@show');
    Route::post('book', 'Api\BookController@store');
    Route::put('book/{id}', 'Api\BookController@update');
    Route::delete('book/{id}', 'Api\BookController@destroy');

    Route::get('user', 'Api\UserController@index');
    Route::get('user/{id}', 'Api\UserController@show');
    Route::post('user', 'Api\UserController@store');
    Route::put('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@destroy');

    Route::get('peminjaman', 'Api\PeminjamanController@index');
    Route::get('peminjaman/{id}', 'Api\PeminjamanController@show');
    Route::post('peminjaman', 'Api\PeminjamanController@store');
    Route::put('peminjaman/{id}', 'Api\PeminjamanController@update');
    Route::delete('peminjaman/{id}', 'Api\PeminjamanController@destroy');

    Route::get('pengembalian', 'Api\PengembalianController@index');
    Route::get('pengembalian/{id}', 'Api\PengembalianController@show');
    Route::post('pengembalian', 'Api\PengembalianController@store');
    Route::put('pengembalian/{id}', 'Api\PengembalianController@update');
    Route::delete('pengembalian/{id}', 'Api\PengembalianController@destroy');
});