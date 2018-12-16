<?php

// Normally I would put this path in the API routes
Route::get('/ping', 'LatencyController@ping');

Route::get('/page', 'LatencyController@index');

Route::get('/{any}', function () { // redirect all endpoints to /page
    return redirect('/page');
})->where('any', '.*');
