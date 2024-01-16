<?php

namespace App\Controllers\Api;

use App\Models\Folder;
use App\Models\Note;
use App\Validators\Notes\CreateNotesValidator;
use App\Validators\Notes\UpdateNoteValidator;
use Enums\SqlOrder; // Assuming SqlOrder is a class in Enums namespace

class NoteController extends ApiController
{
    public function index()
    {
        return $this->response(
            body: Note::where('user_id', '=', authId())
                ->orderBy([
                    'pinned' => SqlOrder::DESC,
                    'completed' => SqlOrder::ASC,
                    'updated_at' => SqlOrder::DESC,
                ])
                ->get()
        );
    }

    public function update(int $id)
    {
        $note = Note::find($id);

        if (!$note || $note->user_id !== authId()) {
            return $this->response(403, [], [
                'message' => 'This resource is forbidden for you'
            ]);
        }

        $data = [
            'title' => $this->request->post('title'),
            'content' => $this->request->post('content'),
            'pinned' => $this->request->post('pinned'),
            'completed' => $this->request->post('completed'),
            'folder_id' => $this->request->post('folder_id'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $validator = new UpdateNoteValidator($note);

        if ($validator->validate($data) && $note->update($data)) {
            return $this->response(body: $note->toArray());
        }

        return $this->response(errors: $validator->getErrors());
    }

    public function destroy(int $id)
    {
        $note = Note::find($id);

        if ($note && $note->user_id !== authId()) {
            return $this->response(403, [], [
                'message' => 'This resource is forbidden for you'
            ]);
        }

        $result = Note::destroy($id);

        if (!$result) {
            return $this->response(422, [], ['message' => 'Oops something went wrong']);
        }

        return $this->response();
    }
}
