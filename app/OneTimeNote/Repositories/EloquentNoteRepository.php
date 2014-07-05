<?php

namespace OneTimeNote\Repositories;

use Exception;

use Request;
use Illuminate\Encryption\Encrypter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

use OneTimeNote\Interfaces\NoteRepositoryInterface;
use OneTimeNote\Models\Note;

class EloquentNoteRepository implements NoteRepositoryInterface {

    /**
     * Find one note by url_id and key
     * @todo Implement more useful action on various exceptions being thrown
     *
     * @param  integer $url_id
     * @param  string  $key
     * @return OneTimeNote\Models\Note
     */
    public function find($url_id, $key)
    {
        $encryption = new Encrypter($url_id . $key);

        try {
            $note              = Note::where('url_id', '=', $url_id)->firstOrFail();
            $note->secure_note = nl2br( $encryption->decrypt($note->secure_note) );
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }

        return $note;
    }

    /**
     * Create a new note
     * @param  array $input Associative array
     * @return OneTimeNote\Models\Note
     */
    public function create($input)
    {
        $url_id     = str_random(16);
        $key        = str_random(16);
        $encryption = new Encrypter($url_id . $key);

        $note              = new Note($input);
        $note->ip_address  = Request::getClientIp();
        $note->url_id      = $url_id;
        $note->secure_note = $encryption->encrypt($input['secure_note']);
        $note->key         = $key;

        if (!$note->save()) {
            /**
             * @todo throw new Exception("Error creating note...", 1);
             */
            return null;
        }

        return $note;
    }

    /**
     * Delete a note by id
     * @param  integer $id
     * @return bool
     */
    public function delete($id)
    {
        $note = Note::find($id);

        if (!$note) {
            /**
             * @todo throw new Exception("This should not be called by accident... MaliciousIntentException ?", 1);
             */
            return false;
        }

        $deleted = $note->delete();
        return $deleted;
    }

    /**
     * @param  integer $days
     * @return bool
     */
    public function deleteNotesOlderThan($days)
    {
        $date        = new Carbon;
        $target_date = $date->subDays($days);
        $deleted     = Note::where('created_at', "<", $target_date)->delete();

        /**
         * Is considered success even if delete() returns false. (i.e. there were no notes within the range to delete)
         */
        return true;
    }

    /**
     * Attempt to retrieve most recently created note for returning user
     * @return OneTimeNote\Models\Note
     */
    public function existingNoteByIpAddress() {
        return Note::where('ip_address', '=', Request::getClientIp())->orderBy('created_at', 'desc')->first();
    }
}
