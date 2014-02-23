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

        /* @TODO - Probably could find a better spot for this, possibly in a lang folder. Need to learn to do this */
        \Config::set('NOTE_HAS_BEEN_READ', 'The note you created has been read and destroyed.');
    }
}