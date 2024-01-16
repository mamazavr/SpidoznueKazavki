<?php
namespace App\Models;

class User extends \Core\Model
{
    protected static ?string $tableName = 'users';
    public static ?string $tableName = 'users';

    public ?string $email, $password, $token, $created_at, $token_expired_at = null;

    public function getUserInfo(): array
    {
        return [
            'email' => $this->email,
            'token' => $this->token
        ];
    }
}
