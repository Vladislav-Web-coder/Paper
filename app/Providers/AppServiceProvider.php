<?php

namespace App\Providers;

<<<<<<< HEAD
use App\Models\Folder;
use App\Observers\NoteObserver;
use App\Observers\FolderObserver;
use App\Models\Note;
use Illuminate\Pagination\Paginator;
=======
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
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
<<<<<<< HEAD
        Note::observe(NoteObserver::class);
        Folder::observe(FolderObserver::class);

        Paginator::useTailwind();
=======
        //
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
    }
}
