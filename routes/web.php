<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('login',  'LoginController@index')->middleware('guest')->name('login');


Route::middleware('auth')->group(function(){

    Route::get('',  'DashboardController@index')->name('dashboard.index');
    Route::get('transaction',  'TransactionController@index')->name('transaction.index');

    Route::middleware('role:ADMIN')->prefix('waste')->group(function(){
        Route::get('',  'WasteController@index')->name('waste.index');
        Route::get('create',  'WasteController@create')->name('waste.create');
        Route::get('{id}/edit',  'WasteController@edit')->name('waste.edit');
        Route::get('{id}',  'WasteController@detail')->name('waste.detail');
    });
});
