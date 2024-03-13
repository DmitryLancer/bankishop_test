<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/upload', [App\Http\Controllers\ImageController::class, 'showUploadForm']);
Route::post('/upload', [App\Http\Controllers\Controller::class, 'uploadImage']);


