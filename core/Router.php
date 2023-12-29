<?php

namespace Core;

class Router
{
    protected static array $routes = [];
    protected static array $params = [];
    protected static array $convertTypes = ['d' => 'int', '.' => 'string'];

    public static function add(string $route, array $params): void
    {
        $route = self::convertToRegex($route);
        self::$routes[$route] = $params;
    }

    public static function dispatch(string $uri): void
    {
        $uri = self::removeQueryVariables($uri);
        $uri = trim($uri, '/');

        try {
            if (self::match($uri)) {
                self::handleRoute();
            } else {
                self::generateJsonResponse(['code' => 404, 'errors' => "Route [$uri] not found"]);
            }
        } catch (\Exception $e) {
            self::generateJsonResponse(['code' => $e->getCode(), 'errors' => $e->getMessage()]);
        }
    }

    protected static function handleRoute(): void
    {
        self::checkRequestMethod();

        $controller = self::getController();
        $action = self::getAction($controller);

        try {
            if ($controller->before($action, self::$params)) {
                $response = call_user_func_array([$controller, $action], self::$params);
                $controller->after($action);
                self::generateJsonResponse(['code' => 200, 'body' => $response]);
            }
        } catch (\Exception $e) {
            self::generateJsonResponse(['code' => $e->getCode(), 'errors' => $e->getMessage()]);
        }
    }

    protected static function getAction(Controller $controller): string
    {
        $action = self::$params['action'] ?? null;

        if (!method_exists($controller, $action)) {
            throw new \Exception("$controller doesn't have '$action'", 404);
        }

        unset(self::$params['action']);

        return $action;
    }

    protected static function getController(): Controller
    {
        $controller = self::$params['controller'] ?? null;

        if (!class_exists($controller)) {
            throw new \Exception("Controller '$controller' doesn't exist!", 404);
        }

        unset(self::$params['controller']);

        return new $controller;
    }

    protected static function checkRequestMethod(): void
    {
        if (array_key_exists('method', self::$params)) {
            $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

            if ($requestMethod !== strtolower(self::$params['method'])) {
                throw new \Exception("Method '$requestMethod' is not allowed for this route", 405);
            }

            unset(self::$params['method']);
        }
    }

    protected static function match(string $uri): bool
    {
        foreach (self::$routes as $route => $params) {
            if (preg_match($route, $uri, $matches)) {
                self::$params = self::buildParams($route, $matches, $params);
                return true;
            }
        }

        return false;
    }

    protected static function buildParams(string $route, array $matches, array $params): array
    {
        preg_match_all('/\(\?P<[\w]+>(\\\\)?([\w\.][\+]*)\)/', $route, $types);
        $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        if (!empty($types)) {
            $lastKey = array_key_last($types);
            $types[$lastKey] = array_map(fn ($value) => str_replace('+', '', $value), $types[$lastKey]);

            foreach ($matches as $name => $match) {
                settype($match, self::$convertTypes[$types[$lastKey][$name]]);
                $params[$name] = $match;
            }
        }

        return $params;
    }

    protected static function removeQueryVariables(string $uri): string
    {
        return preg_replace('/([\w\/\-]+)\?([\w\/=\d%*&\?]+)/i', '$1', $uri);
    }

    protected static function convertToRegex(string $route): string
    {
        $route = preg_replace('/\//', '\\/', $route);
        return "/^$route$/i";
    }

    protected static function generateJsonResponse(array $response): void
    {
        http_response_code($response['code']);
        header('Content-Type: application/json');
        echo json_encode(['data' => $response['body'], 'errors' => $response['errors']]);
        exit;
    }
}
