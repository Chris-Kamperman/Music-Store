<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;

// Public Routes For Loging In And Registering
Route::post('/register', UserController::class . '@register');
Route::post('/login', UserController::class . '@login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', UserController::class . '@logout');


    // Protected Routes For Albums, Artists And Songs
    Route::resource('albums', AlbumController::class);
    Route::post('/albums/{id}/purchase', AlbumController::class . '@buyAlbum');
    Route::get('/user/albums', AlbumController::class . '@getUserAlbums');

    Route::get('/artists', ArtistController::class . '@index');
    Route::post('/artists', ArtistController::class . '@store');

    Route::get('/songs', SongController::class . '@index');
    Route::get('/songs/{id}', SongController::class . '@show');
    Route::post('/songs', SongController::class . '@store');

    Route::post('/artists', ArtistController::class . '@store');
});