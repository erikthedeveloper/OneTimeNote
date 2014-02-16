<?php

namespace OneTimeNote\Controllers;

use Illuminate\Encryption\Encrypter;
use OneTimeNote\Repositories\NoteRepositoryInterface as Note;

class NoteController extends \Controller {
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

	public function getNote($url_id, $key)
	{
        $note = $this->note->find($url_id, $key);

        if (!$note) {
            return \Response::json(array('message' => 'Note not found'), 404);
        }

        $this->note->delete($note->id);

		return \Response::json($note);
	}

    public function postNote()
	{
        // Check if user has already submitted a note within the allotted time
        $existing_note = $this->note->existingNote();
        if ($existing_note) {
            $now = new \Carbon\Carbon();
            if($now->diffInMinutes($existing_note->created_at) < 1) {
                return \Response::json(array('message' => 'Note not created - please wait one full minute between note submissions.'), 403);
            };
        }


        $note = $this->note->create(\Input::all());

        if (!$note) {
            return \Response::json(array('message' => 'Note not created - please check fields and try again.'), 400);
        }

        return \Response::json(array('message' => 'Note Created', 'note_url' => \Request::root() . '/note/' . $note->url_id . '/' . $note->key), 201);
	}
}