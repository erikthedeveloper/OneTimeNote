<?php

namespace OneTimeNote\Repositories;

use Illuminate\Encryption\DecryptException;
use OneTimeNote\Interfaces\NoteRepositoryInterface;
use Illuminate\Encryption\Encrypter;
use OneTimeNote\Models\Note;

class EloquentNoteRepository implements NoteRepositoryInterface {

    public function find($url_id, $key)
    {
        $note = Note::where('url_id', '=', $url_id)->first();

        if (!$note) {
           return null;
        }

        $encryption = new Encrypter($url_id . $key);

        try {
            $note->secure_note = $encryption->decrypt($note->secure_note);
        } catch (DecryptException $e) {
            return null;
        }

        return $note;
    }

    public function create($input)
    {
        $url_id = \Str::random(16);
        $key = \Str::random(16);
        $encryption = new Encrypter($url_id . $key);

        $note = new Note;
        $note->fill($input);
        $note->ip_address = \Request::getClientIp();
        $note->url_id = $url_id;
        $note->secure_note = $encryption->encrypt($input['secure_note']);

        if (!$note->save()) {
            return null;
        }

        $note->key = $key;

        return $note;
    }

    public function delete($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return null;
        }

        $note->delete();
    }

    public function deleteNotesOlderThan($days)
    {
        $date = new \Carbon\Carbon();
        $date = $date->subDays($days);

        $notes = Note::where('created_at', "<", $date);

        if ($notes->count() == 0) {
            return null;
        }

        $notes->delete();

        return true;
    }

    public function existingNoteByIpAddress() {
        return Note::where('ip_address', '=', \Request::getClientIp())->orderBy('created_at', 'desc')->first();
    }
}
