<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ApplicantController::class)->group(function () {
    Route::post('/register', 'store');
});

Route::controller(ApplicationController::class)->group(function () {
    Route::post('/apply', 'ApplyPosting');
});

Route::get("/getCSRF", function () {
    return csrf_token();
});
