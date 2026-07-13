<?php

<<<<<<< HEAD
use App\Http\Controllers\Api\TelegramWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Middleware\CheckTwoFactor;
=======
use Illuminate\Support\Facades\Route;
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86

Route::get('/', function () {
    return view('welcome');
});
<<<<<<< HEAD

Route::middleware('auth')->group(function () {
    Route::middleware([CheckTwoFactor::class])->group(function () {

        Route::group(['prefix' => 'settings/2fa', 'as' => '2fa.'], function () {
            Route::get('/setup', [TwoFactorController::class, 'showSetup'])->name('setup');
            Route::post('enable', [TwoFactorController::class, 'enable'])->name('enable');
            Route::post('disable', [TwoFactorController::class, 'disable'])->name('disable');
        });

        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/read-all', [NotificationController::class, 'markAsReadAll'])->name('markAsReadAll');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::delete('clear-all', [NotificationController::class, 'clearAll'])->name('clearAll');
        });

        Route::middleware('verified')->group(function () {
            Route::patch('/notes/{note}/pin', [NoteController::class, 'pin'])->name('notes.pin');
            Route::resource('notes', NoteController::class);
            Route::resource('folders', FolderController::class)->except(['create', 'edit']);

            Route::middleware(['web','auth'])->get('/telegram/connect', [TelegramWebhookController::class, 'connect'])->name('telegram.connect');

            Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
                Route::get('/{tab?}', [SettingsController::class, 'show'])->name('show');
                Route::post('/privacy/confirm', [SettingsController::class, 'confirmPassword'])->name('confirm_password');
                Route::patch('/settings', [SettingsController::class, 'update'])->name('update');
            });
        });

    });
});

require __DIR__ . '/auth.php';
=======
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
