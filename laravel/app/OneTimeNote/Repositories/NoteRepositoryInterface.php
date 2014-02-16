<?php

namespace OneTimeNote\Repositories;

interface NoteRepositoryInterface {

    public function find($url_id, $key);

    public function create($input);

    public function delete($id);

    public function existingNote();
}