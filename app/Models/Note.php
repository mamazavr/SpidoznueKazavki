<?php

namespace App\Models;

use Core\Model;

class Note extends Model
{
    protected int $id;
    protected int $user_id;
    protected int $folder_id;
    protected string $title;
    protected string $content;
    protected bool $pinned = false;
    protected bool $completed = false;

    // Additional properties and methods as needed
}
