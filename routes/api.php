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

    
    //SERVICES ROUTES
    Route::get('/currency', 'API\CurrencyController@fillBD');
    Route::get('/valute', 'API\CurrencyController@getValute');
    Route::get('/filter', 'API\CurrencyController@getFilter');
    