<?php
namespace OneTimeNote;

use Illuminate\Support\ServiceProvider;

class OneTimeNoteServiceProvider extends ServiceProvider {
    public function register()
    {
        $this->app->bind(
            'OneTimeNote\Repositories\NoteRepositoryInterface',
            'OneTimeNote\Repositories\EloquentNoteRepository'
        );
    }

}