<?php

namespace App\Validators\Notes;

use App\Models\Folder;
use App\Validators\BaseValidator;
use Enums\SQL;

class CreateNotesValidator extends BaseValidator
{
    protected array $rules = [
        'title' => '/[\w\d\s\(\)\-]{3,}/i',
        'content' => '/.*$/i',
        'folder_id' => '/\d+/i'
    ];

    protected array $errors = [
        'title' => 'Title should contain characters, numbers, and _-() symbols and have a length of more than 2 symbols',
        'folder_id' => 'Folder ID should exist in the request and have type int'
    ];

    protected array $skip = ['user_id', 'updated_at'];

    public function validateFolderId(int $id): bool
    {
        return Folder::where('id', '=', $id)
            ->startCondition()
            ->andWhere(function ($query) use ($id) {
                $query->where('user_id', '=', authId())
                    ->orWhere('user_id', SQL::IS_OPERATOR->value, SQL::NULL->value);
            })
            ->endCondition()
            ->exists();
    }

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            $this->validateFolderId($fields['folder_id']),
            !$this->checkTitleOnDuplication(
                $fields['title'],
                $fields['folder_id'],
                $fields['user_id']
            ),
            $this->validateBooleanValue($fields, 'pinned'),
            $this->validateBooleanValue($fields, 'completed'),
        ];

        return !in_array(false, $result);
    }
}
