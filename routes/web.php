<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('notes', NoteController::class);
    Route::resource('folders', FolderController::class)->except(['create', 'edit']);

    Route::get('/dashboard', [DashboardController::class])
        ->name('dashboard');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/{tab?}', [SettingsController::class, 'show'])->name('show');
        Route::post('/privacy/confirm', [SettingsController::class, 'confirmPassword'])->name('confirm_password');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('update');
    });
});

require __DIR__ . '/auth.php';
