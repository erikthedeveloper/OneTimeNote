<?php

namespace OneTimeNote\Services;
use Illuminate\Support\Facades\Config;
use OneTimeNote\Interfaces\NoteMailerInterface;
use Illuminate\Support\Facades\Mail;

class SwiftMailerNoteService implements NoteMailerInterface {

    public function to($email, $subject) {
        $data = array('SITE_IMPLEMENTATION_URL' => Config::get('onetimenote.SITE_IMPLEMENTATION_URL'));

        Mail::send(array('html' => 'Mailer.default'), $data, function($message) use ($email, $subject)
        {
            $message->to($email)->subject($subject);
        });
    }
}