<?php

namespace App\Controllers;

use App\Models\Note;
use App\Validators\Notes\NoteValidator;
use App\Validators\Notes\UpdateNoteValidator;
use Core\Controller;

class NoteController extends Controller
{
    public function viewAll()
    {
        $allNotes = Note::all();

        return $this->response(200, ['notes' => $allNotes]);
    }

    public function viewById($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return $this->response(404, ['error' => 'Note not found']);
        }

        return $this->response(200, ['note' => $note]);
    }

    public function create()
    {
        $requestData = requestBody();

        $validator = new NoteValidator(new Note);
        if (!$validator->validateCreate($requestData)) {
            return $this->response(422, ['errors' => $validator->getErrors()]);
        }

        $note = Note::create([
            'title' => $requestData['title'],
            'content' => $requestData['content'],
        ]);

        return $this->response(201, ['message' => 'Note created successfully', 'note' => $note]);
    }

    public function update($id)
    {
        $requestData = requestBody();

        $validator = new UpdateNoteValidator(new Note);
        if (!$validator->validateUpdate($requestData)) {
            return $this->response(422, ['errors' => $validator->getErrors()]);
        }

        $note = Note::find($id);

        if (!$note) {
            return $this->response(404, ['error' => 'Note not found']);
        }

        $note->update([
            'title' => $requestData['title'],
            'content' => $requestData['content'],
        ]);

        return $this->response(200, ['message' => 'Note updated successfully', 'note' => $note]);
    }

    public function delete($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return $this->response(404, ['error' => 'Note not found']);
        }

        $note->delete();

        return $this->response(200, ['message' => 'Note deleted successfully']);
    }
}
