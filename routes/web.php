<?php

use Illuminate\Support\Facades\Route;
Route::get('/{all}', function() {
    return view('app');
})->name('home')->where('all', '(.*)');




