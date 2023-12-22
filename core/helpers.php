<?php

use Core\Config;

function requestBody(): array
{
    $data = [];
    $requestBody = file_get_contents("php://input");

    if (!empty($requestBody)) {
        $data = json_decode($requestBody, true);
    }

    return $data;
}

function json_response($code = 200, array $data = []): string
{

    header_remove();
    http_response_code($code);
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');

    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    );


    header('Status: ' . $status[$code]);

    // return the encoded json
    return json_encode(array(
        'code' => $code,
        'status' => $status[$code],
        ...$data
    ));
}

function config(string $name): string|null
{
    return Config::get($name);
}

function db(): PDO
{
    return \Core\Db::connect();
}

