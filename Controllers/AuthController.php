<?php

namespace App\Controllers;

use App\Models\User;
use App\Validators\Users\AuthValidator;
use App\Validators\Users\RegisterValidator;
use Core\Controller;
use Core\Router;

class AuthController extends Controller
{
public function signup(): void
{
$data = requestBody();
$validator = new RegisterValidator();

if ($validator->validate($data)) {
$id = User::create([
...$data,
'password' => password_hash($data['password'], PASSWORD_BCRYPT)
]);

Router::json(['user' => User::find($id)->toArray()], 200);
} else {
Router::json(['errors' => $validator->getErrors()], 422);
}
}

public function signin(): void
{
$data = requestBody();
$validator = new AuthValidator();

if ($validator->validate($data)) {
$user = User::findBy('email', $data['email']);
if ($user && password_verify($data['password'], $user->password)) {
$token = generateToken(); // Implement a function to generate a token.

Router::json(['token' => $token], 200);
} else {
Router::json(['errors' => 'Invalid credentials'], 401);
}
} else {
Router::json(['errors' => $validator->getErrors()], 422);
}
}
}
