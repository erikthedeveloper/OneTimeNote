<?php

namespace OneTimeNote\Services;

use OneTimeNote\Interfaces\NoteMailerInterface;
use Illuminate\Support\Facades\Mail;

class SwiftMailerNoteService implements NoteMailerInterface {

    public function to($email) {
        Mail::send(array('html' => 'mailer.default'), array(), function($message) use ($email)
        {
            $message->to($email)->subject('The note you created has been read and destroyed.');
        });
    }
}