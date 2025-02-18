<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureSongOwned;

// Public Routes For Loging In And Registering
Route::post('/register', UserController::class . '@register');
Route::post('/login', UserController::class . '@login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', UserController::class . '@logout');


    // Album Routes
    Route::get('/albums', AlbumController::class . '@index');
    Route::get('/albums/{id}', AlbumController::class . '@show');
    Route::post('/albums/{id}/purchase', AlbumController::class . '@buyAlbum');
    Route::get('/user/albums', AlbumController::class . '@getUserAlbums');

    // Artist Routes
    Route::get('/artists', ArtistController::class . '@index');

    // Song Routes
    Route::get('/songs/{id}/download', SongController::class . '@download')
        ->middleware(EnsureSongOwned::class);

    // Routes For Administrators
    Route::group(['middleware' => EnsureAdmin::class], function () {
        Route::Post('/albums', AlbumController::class . '@store');

        Route::post('/artists', ArtistController::class . '@store');

        Route::post('/songs', SongController::class . '@store');
    });
});