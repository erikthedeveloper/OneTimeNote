<?php
namespace OneTimeNote;

use Illuminate\Support\ServiceProvider;

class OneTimeNoteServiceProvider extends ServiceProvider {
    public function register()
    {
        $this->app->bind(
            'OneTimeNote\Interfaces\NoteRepositoryInterface',
            'OneTimeNote\Repositories\EloquentNoteRepository'
        );

        $this->app->bind(
            'OneTimeNote\Interfaces\NoteMailerInterface',
            'OneTimeNote\Services\SwiftMailerNoteService'
        );

        \View::addLocation(app('path').'/OneTimeNote/Views');
    }
}