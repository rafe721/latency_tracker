<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

// Normally I would put this path in the API routes
Route::get('/ping', 'LatencyController@ping');

Route::get('/page', 'LatencyController@index');

Route::get('/{any}', function () {
    return redirect('/page');
})->where('any', '.*');
