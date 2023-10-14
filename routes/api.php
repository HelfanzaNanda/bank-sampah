<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'LoginController@login');
Route::post('logout', 'LoginController@logout');

Route::middleware("auth:sanctum")->group(function() {
    Route::prefix('waste')->group(function(){
        Route::post('datatables',  'WasteController@datatables')->middleware('role:ADMIN');
        Route::post('options',  'WasteController@options');
        Route::post('',  'WasteController@store')->middleware('role:ADMIN');
        Route::get('{id}',  'WasteController@find')->middleware('role:ADMIN');
        Route::put('{id}',  'WasteController@update')->middleware('role:ADMIN');
        Route::put('{id}/status',  'WasteController@changeStatus')->middleware('role:ADMIN');
        Route::delete('{id}',  'WasteController@delete')->middleware('role:ADMIN');
    });

    Route::prefix('transaction')->group(function(){
        Route::post('',  'TransactionController@store')->middleware('role:USER');
    });
    Route::prefix('dashboard')->group(function(){
        Route::get('',  'DashboardController@chart')->middleware('role:USER|ADMIN');
        Route::post('datatables',  'DashboardController@datatables')->middleware('role:USER');
    });

    Route::prefix('files')->controller('FileController')->group(function(){
        // Route::get('', 'download');
        Route::post('upload', 'upload');
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
