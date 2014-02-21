<?php

namespace OneTimeNote\Repositories;

use OneTimeNote\Contracts\NoteRepositoryInterface;
use Illuminate\Encryption\Encrypter;
use OneTimeNote\Models\Note;

class EloquentNoteRepository implements NoteRepositoryInterface {

    public function find($url_id, $key)
    {
        $note =  Note::where('url_id', '=', $url_id)->first();

        if (!$note) {
           return null;
        }

        $encryption = new Encrypter($url_id . $key);
        $note->secure_note = $encryption->decrypt($note->secure_note);

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

        if ($note->save()) {
            $note->key = $key;

            return $note;
        }

        return null;
    }

    public function delete($id)
    {
        Note::find($id)->delete();
    }

    public function deleteNotesOlderThan($days)
    {
        $date = new \Carbon\Carbon();
        $date = $date->subDays($days);
        $note = Note::where('created_at', "<", $date)->get();
    }

    public function existingNote() {
        return Note::where('ip_address', '=', \Request::getClientIp())->orderBy('created_at', 'desc')->first();
    }
}
