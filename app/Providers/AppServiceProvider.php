<?php

namespace App\Providers;

use App\Models\Folder;
use App\Observers\NoteObserver;
use App\Observers\FolderObserver;
use App\Models\Note;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Note::observe(NoteObserver::class);
        Folder::observe(FolderObserver::class);

        Event::listen(NotificationSent::class, function (NotificationSent $event) {
            if (method_exists($event->notifiable, 'getAttribute')) {
                Cache::tags(["user:{$event->notifiable->id}:notifications"])->flush();
            }
        });

        Paginator::useTailwind();
    }
}
