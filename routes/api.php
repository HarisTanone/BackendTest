<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

Route::apiResource('author', AuthorController::class);
Route::apiResource('book', BookController::class);
Route::get('author/{author}/books', [AuthorController::class, 'books']);
