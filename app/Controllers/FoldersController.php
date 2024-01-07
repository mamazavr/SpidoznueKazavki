<?php

namespace App\Controllers;

use App\Validators\Notes\CreateNotesValidator;
use App\Validators\Notes\UpdateNoteValidator;
use Core\Controller;
use Core\Router;

class FoldersController extends Controller
{
    private Router $router;
    private CreateNotesValidator $createNotesValidator;
    private UpdateNoteValidator $updateNoteValidator;

    public function __construct(Router $router, CreateNotesValidator $createNotesValidator, UpdateNoteValidator $updateNoteValidator)
    {
        $this->router = $router;
        $this->createNotesValidator = $createNotesValidator;
        $this->updateNoteValidator = $updateNoteValidator;
    }

    public function viewAll(): void
    {
        $this->router->json(['message' => 'View all folders method'], Router::HTTP_OK);
    }

    public function viewById(int $id): void
    {
        $this->router->json(['message' => "View folder with ID $id method"], Router::HTTP_OK);
    }

    public function create(): void
    {
        $data = requestBody();
        $validationErrors = $this->createNotesValidator->validate($data);

        if (!empty($validationErrors)) {
            $this->router->json(['errors' => $validationErrors], Router::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->router->json(['message' => 'Create folder method'], Router::HTTP_OK);
    }

    public function update(int $id): void
    {
        $data = requestBody();
        $validationErrors = $this->updateNoteValidator->validate($data);

        if (!empty($validationErrors)) {
            $this->router->json(['errors' => $validationErrors], Router::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->router->json(['message' => "Update folder with ID $id method"], Router::HTTP_OK);
    }

    public function delete(int $id): void
    {
        $this->router->json(['message' => "Delete folder with ID $id method"], Router::HTTP_OK);
    }
}
