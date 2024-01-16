<?php
\Core\Router::add('api/auth/registration', [
    'controller' => \App\Controllers\Api\AuthController::class,
    'action' => 'signup',
    'method' => 'POST'
]);
\Core\Router::add('api/auth/login', [
    'controller' => \App\Controllers\Api\AuthController::class,
    'action' => 'signin',
    'method' => 'POST'
]);
\Core\Router::add('api/folders', [
    'controller' => \App\Controllers\Api\FoldersController::class,
    'action' => 'index',
    'method' => 'GET'
]);
\Core\Router::add('api/folders/{id:\d+}', [
    'controller' => \App\Controllers\Api\FoldersController::class,
    'action' => 'show',
    'method' => 'GET'
]);

\Core\Router::add('api/folders/{id:\d+}/notes', [
    'controller' => \App\Controllers\Api\FoldersController::class,
    'action' => 'notes',
    'method' => 'GET'
]);

\Core\Router::add('api/folders/store', [
    'controller' => \App\Controllers\Api\FoldersController::class,
    'action' => 'store',
    'method' => 'POST'
]);
\Core\Router::add('api/folders/{id:\d+}/update', [
    'controller' => \App\Controllers\Api\FoldersController::class,
    'action' => 'update',
    'method' => 'PUT'
]);
\Core\Router::add('api/folders/{id:\d+}/destroy', [
    'controller' => \App\Controllers\Api\FoldersController::class,
    'action' => 'destroy',
    'method' => 'DELETE'
]);
\Core\Router::add('api/notes', [
    'controller' => \App\Controllers\Api\NotesController::class,
    'action' => 'index',
    'method' => 'GET'
]);
\Core\Router::add('api/notes/{id:\d+}', [
    'controller' => \App\Controllers\Api\NotesController::class,
    'action' => 'show',
    'method' => 'GET'
]);
\Core\Router::add('api/notes/store', [
    'controller' => \App\Controllers\Api\NotesController::class,
    'action' => 'store',
    'method' => 'POST'
]);
\Core\Router::add('api/notes/{id:\d+}/update', [
    'controller' => \App\Controllers\Api\NotesController::class,
    'action' => 'update',
    'method' => 'PUT'
]);
\Core\Router::add('api/notes/{id:\d+}/destroy', [
    'controller' => \App\Controllers\Api\NotesController::class,
    'action' => 'destroy',
    'method' => 'DELETE'
]);

\Core\Router::add('api/notes/{note_id:\d+}/share/add', [
    'controller' => \App\Controllers\Api\SharedNotesController::class,
    'action' => 'add',
    'method' => 'POST'
]);

\Core\Router::add('api/notes/{note_id:\d+}/share/remove', [
    'controller' => \App\Controllers\Api\SharedNotesController::class,
    'action' => 'remove',
    'method' => 'POST'
]);
