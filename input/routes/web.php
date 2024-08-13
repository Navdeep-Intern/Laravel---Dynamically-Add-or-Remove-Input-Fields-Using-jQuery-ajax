<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('addmore',[HomeController::class,'addMore']);
Route::post('addmore',[HomeController::class,'addMorePost']);