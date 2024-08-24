<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// route for author
Route::group(['prefix' => 'authors'], function () {
    Route::get('/', [AuthorController::class, 'index']);
    Route::get('/{id}', [AuthorController::class, 'show']);
    Route::post('/', [AuthorController::class, 'store']);
    Route::put('/{id}', [AuthorController::class, 'update']);
    Route::delete('/{id}', [AuthorController::class, 'destroy']);
    Route::get('/{id}/books', [AuthorController::class, 'authorBooks']);
});

// route for books
Route::group(['prefix' => 'books'], function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::post('/', [BookController::class, 'store']);
    Route::put('/{id}', [BookController::class, 'update']);
    Route::delete('/{id}', [BookController::class, 'destroy']);
});
