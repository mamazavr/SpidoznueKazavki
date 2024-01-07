<?php

namespace App\Validators\Notes;

use App\Models\Note;
use App\Validators\BaseValidator;

class NoteValidator extends BaseValidator
{
    protected array $createRules = [
        'title' => '/^.+$/i',
        'content' => '/^.+$/i',
    ];

    protected array $updateRules = [
        'title' => '/[\w\d\s\(\)\-]{3,}/i',
        'content' => '/.*$/i',
    ];

    protected array $updateErrors = [
        'title' => 'Title should contain characters, numbers and _-() symbols and have a length of more than 2 symbols',
    ];

    public function __construct(protected Note $note) {}

    public function validateCreate(array $fields): bool
    {
        $this->setRules($this->createRules);
        return $this->validate($fields);
    }

    public function validateUpdate(array $fields): bool
    {
        $this->setRules($this->updateRules);
        $result = [
            parent::validate($fields),
            $this->validateBooleanValue($fields, 'pinned'),
            $this->validateBooleanValue($fields, 'completed'),
        ];

        $this->errors = array_merge($this->errors, $this->updateErrors);

        return !in_array(false, $result);
    }
}
