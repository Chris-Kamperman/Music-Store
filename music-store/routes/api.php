<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('albums', AlbumController::class);

Route::get('/artists', ArtistController::class . '@index');
Route::get('/artists/{id}', ArtistController::class . '@show');
Route::post('/artists', ArtistController::class . '@store');