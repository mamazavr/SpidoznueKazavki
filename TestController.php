<?php

namespace App\Controllers;

use Core\Controller;

class TestController extends Controller
{
    public function index()
    {
        $message = 'Привет из TestController';

        return $this->response(200, ['message' => $message]);
    }

    public function anotherMethod()
    {
        $message = 'Это еще один метод в TestController';

        return $this->response(200, ['message' => $message]);
    }
}
