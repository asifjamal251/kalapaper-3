<?php

use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Route;

Route::name('web.')->group(function() {

    Route::controller(PageController::class)->group(function(){
        Route::get('/', 'home')->name('home');
    });

});