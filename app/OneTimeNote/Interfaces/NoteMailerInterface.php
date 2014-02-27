<?php

namespace OneTimeNote\Interfaces;

interface NoteMailerInterface {

    public function to($email, $subject);

}