<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update'])->middleware(['pemilik-postingan']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware(['pemilik-postingan']);

    Route::post('/comment', [CommentController::class, 'store']);
    Route::patch('/comment/{id}', [CommentController::class, 'update'])->middleware('pemilik-komentar');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware('pemilik-komentar');
});


Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);




Route::post('/login', [AuthenticationController::class, 'login']);

// Route::get('/posts2/{id}', [PostController::class, 'show2']);
