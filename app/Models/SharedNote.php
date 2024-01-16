<?php
namespace App\Models;

class SharedNote extends \Core\Model
{
    protected static ?string $tableName = 'shared_notes';
    public static ?string $tableName = 'shared_notes';

    public int $id, $user_id, $note_id;
}
