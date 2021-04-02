<?php

use App\Http\Controllers\BooksController;
use Illuminate\Support\Facades\Route;

Route::post('/books', [BooksController::class, 'store']);
Route::patch('/books/{book}', [BooksController::class, 'update']);
