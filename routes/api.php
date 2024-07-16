<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::prefix('1.0')->group(function () {

    // Routes for managing books
    Route::resource('books', BookController::class);

    // Routes for managing authors
    Route::resource('authors', AuthorController::class);
    
    // Routes for managing authors
    Route::get('/authors/{id}/books', [AuthorController::class, 'getBooksByAuthor']);
});
