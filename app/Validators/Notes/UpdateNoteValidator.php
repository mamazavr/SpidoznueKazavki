<?php
namespace App\Validators\Notes;

use App\Models\Folder;
use App\Models\Note;
use App\Validators\BaseValidator;

class UpdateNoteValidator extends Base
{
    protected array $rules = [
        'title' => '/[\w\d\s\(\)\-]{3,}/i',
        'content' => '/.*$/i',
    ];

    public function __construct(protected Note $note) {}

    protected array $errors = [
        'title' => 'Title should contain characters, numbers and _-() symbols and have a length of more than 2 symbols',
    ];

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            $this->validateBooleanValue($fields, 'pinned'),
            $this->validateBooleanValue($fields, 'completed'),
        ];

        return !in_array(false, $result);
    }
}