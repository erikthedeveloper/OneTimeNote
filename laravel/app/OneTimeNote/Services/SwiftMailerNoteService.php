<?php

namespace OneTimeNote\Services;

use OneTimeNote\Interfaces\NoteMailerInterface;
use Illuminate\Support\Facades\Mail;

class SwiftMailerNoteService implements NoteMailerInterface {

    public function to($email, $subject) {
        Mail::send(array('html' => 'mailer.default'), array(), function($message) use ($email, $subject)
        {
            $message->to($email)->subject($subject);
        });
    }
}