<?php

use App\Http\Controllers\CinemaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CinemaController::class, 'home']);
Route::get('/movie/{slug}', [CinemaController::class, 'movie']);
Route::get('/tag/{slug}', [CinemaController::class, 'tag']);

// 404 fallback
Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});
