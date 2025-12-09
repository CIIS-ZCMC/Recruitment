<?php

use App\Http\Controllers\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ApplicantController::class)->group(function () {
    Route::post('/register', 'store');
});
Route::get("/getCSRF", function () {
    return csrf_token();
});
