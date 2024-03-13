<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/images', [App\Http\Controllers\ImageController::class, 'showImages'])->name('images');
Route::get('/upload', [App\Http\Controllers\ImageController::class, 'showUploadForm']);
Route::post('/upload', [App\Http\Controllers\ImageController::class, 'uploadImage']);
Route::get('/download/{id}', [App\Http\Controllers\ImageController::class, 'downloadImage'])->name('download.image');


Route::get('/api/images', [App\Http\Controllers\ImageController::class, 'getImagesJson']);
Route::get('/api/images/{id}', [App\Http\Controllers\ImageController::class, 'getImageJson']);


