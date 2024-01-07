<?php

use App\Controllers\FoldersController;
use App\Controllers\AuthController;

// Group FoldersController routes
\Core\Router::group('api/folders', function () {
\Core\Router::add('', [
'controller' => FoldersController::class,
'action' => 'viewAll',
'method' => 'GET'
]);

\Core\Router::add('{id}', [
'controller' => FoldersController::class,
'action' => 'viewById',
'method' => 'GET'
]);

\Core\Router::add('', [
'controller' => FoldersController::class,
'action' => 'create',
'method' => 'POST'
]);

\Core\Router::add('{id}', [
'controller' => FoldersController::class,
'action' => 'update',
'method' => 'PUT'
]);

\Core\Router::add('{id}', [
'controller' => FoldersController::class,
'action' => 'delete',
'method' => 'DELETE'
]);
});

// Group AuthController routes
\Core\Router::group('api/auth', function () {
\Core\Router::add('registration', [
'controller' => AuthController::class,
'action' => 'signup',
'method' => 'POST'
]);

\Core\Router::add('login', [
'controller' => AuthController::class,
'action' => 'signin',
'method' => 'POST'
]);
});
