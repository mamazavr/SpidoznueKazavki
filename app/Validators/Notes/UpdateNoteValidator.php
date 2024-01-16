<?php

namespace App\Validators\Notes;

use App\Models\Folder;
use App\Validators\BaseValidator;
use App\Models\Note;
use Enums\SQL;

class UpdateNoteValidator extends BaseValidator
{
    protected array $rules = [
        'title' => '/[\w\d\s\(\)\-]{3,}/i',
        'content' => '/.*$/i',
    ];

    protected array $errors = [
        'title' => 'Title should contain characters, numbers, and _-() symbols and have a length of more than 2 symbols',
    ];

    public function __construct(protected Note $note)
    {
    }

    protected array $skip = ['user_id', 'updated_at', 'pinned', 'completed'];

    public function validateFolderId(array $fields): bool
    {
        if (empty($fields['folder_id'])) {
            return true;
        }

        return Folder::where('id', '=', $fields['folder_id'])
            ->startCondition()
            ->andWhere(function ($query) {
                $query->where('user_id', '=', authId())
                    ->orWhere('user_id', SQL::IS_OPERATOR->value, SQL::NULL->value);
            })
            ->endCondition()
            ->exists();
    }

    public function validateTitle(array $fields): bool
    {
        if (!isset($fields['title'])) {
            return true;
        }

        $result = preg_match('/[\w\d\s\(\)\-]{3,}/i', $fields['title']);

        if (!$result) {
            $this->setError('title', 'Title should contain characters, numbers, and _-() symbols and have a length of more than 2 symbols');
        }

        return $result && $this->checkTitleOnDuplication(
                $fields['title'],
                $fields['folder_id'] ?? $this->note->folder_id,
                $this->note->user_id
            );
    }

    public function validateBooleanValue(array $fields, string $key): bool
    {
        if (empty($fields[$key])) {
            return true;
        }

        return is_bool($fields[$key]);
    }

    public function validate(array $fields = []): bool
    {
        return !in_array(
            false,
            [
                parent::validate($fields),
                $this->validateFolderId($fields),
                $this->validateTitle($fields),
                $this->validateBooleanValue($fields, 'pinned'),
                $this->validateBooleanValue($fields, 'completed'),
            ]
        );
    }
}
