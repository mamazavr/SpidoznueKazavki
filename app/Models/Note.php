<?php
namespace App\Models;

class Note extends \Core\Model
{
    protected static ?string $tableName = 'notes';
    public static ?string $tableName = 'notes';

    public int $user_id, $folder_id;
    public bool $pinned, $completed;
    public string $title, $created_at, $updated_at;
    public ?string $content;
}
