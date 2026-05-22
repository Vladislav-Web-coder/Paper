<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\FolerController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('notes', NoteController::class);
    Route::resource('folders', FolerController::class);
});

// Route::get('/dashboard', function () {});
