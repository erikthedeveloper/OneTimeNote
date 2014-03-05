<?php

namespace OneTimeNote\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
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
            return Response::json(array('message' => Lang::get('onetimenote.messages.NOTE_NOT_FOUND')), 404);
        }

        if ($note->email) {
            $this->mail->to($note->email, Lang::get('onetimenote.mailer.EMAIL_SUBJECT'));
        }

        $note->message = 'Note Destroyed';
        $this->note->delete($note->id);

		return Response::json($note);
	}

    public function postNote()
	{
        // Check if user has already submitted a note within the allotted time
        $existing_note = $this->note->existingNoteByIpAddress();
        if ($existing_note) {
            // @TODO - Possibly move all of this to repository to decouple from controller, possibly a before filter?
            $now = new Carbon();
            if($now->diffInMinutes($existing_note->created_at) < Config::get('onetimenote.NOTE_POST_DURATION')) {
                return Response::json(array('message' => Lang::get('onetimenote.messages.NOTE_POST_DURATION', array('time' => 'one full minute'))), 403);
            };
        }

        $note = $this->note->create(Input::all());

        if (!$note) {
            return Response::json(array('message' => Lang::get('onetimenote.messages.NOTE_VALIDATION_ERROR')), 400);
        }

        return Response::json(array('message' => 'Note Created', 'note_url' => Config::get('onetimenote.SITE_IMPLEMENTATION_URL') . $note->url_id . '/' . $note->key), 201);
	}
}