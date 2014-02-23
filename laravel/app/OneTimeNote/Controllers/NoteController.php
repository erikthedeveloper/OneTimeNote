<?php

namespace OneTimeNote\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;
use OneTimeNote\Interfaces\NoteRepositoryInterface as Note;
use OneTimeNote\Interfaces\NoteMailerInterface as Mailer;

class NoteController extends \Controller {
    protected $note;
    protected $mail;

    public function __construct(Note $note, Mailer $mail)
    {
        $this->note = $note;
        $this->mail = $mail;
    }

	public function getNote($url_id, $key)
	{
        $note = $this->note->find($url_id, $key);

        if (!$note) {
            return Response::json(array('message' => 'Note not found'), 404);
        }

        if ($note->email) {
            $this->mail->to($note->email, \Config::get('NOTE_HAS_BEEN_READ'));
        }

        $this->note->delete($note->id);

		return \Response::json($note);
	}

    public function postNote()
	{
        // Check if user has already submitted a note within the allotted time
        $existing_note = $this->note->existingNoteByIpAddress();
        if ($existing_note) {
            // @TODO - Possibly move all of this to repository to decouple from controller
            $now = new Carbon();
            if($now->diffInMinutes($existing_note->created_at) < 1) {
                return Response::json(array('message' => 'Note not created - please wait one full minute between note submissions.'), 403);
            };
        }

        $note = $this->note->create(Input::all());

        if (!$note) {
            return Response::json(array('message' => 'Note not created - please check fields and try again.'), 400);
        }

        return Response::json(array('message' => 'Note Created', 'note_url' => Request::root() . '/note/' . $note->url_id . '/' . $note->key), 201);
	}
}