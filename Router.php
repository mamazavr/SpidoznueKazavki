<?php

namespace Core;

class Router
{
    private static array $routes = [];
    private static array $params = [];
    private static array $convertTypes = [
        'd' => 'int',
        '.' => 'string'
    ];

    public static function add(string $route, array $params): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z_]+):([^}]+)\}/', '(?P<$1>$2)', $route);
        $route = "/^$route$/i";

        static::$routes[$route] = $params;
    }

    public static function dispatch(string $uri): string
    {
        $uri = static::removeQueryVariables($uri);
        $uri = trim($uri, '/');

        try {
            if (static::match($uri)) {
                // check HTTP method
                static::checkRequestMethod();

                // get controller
                $controller = static::getController();
                $action = static::getAction($controller);

                if ($controller->before($action, static::$params)) {
                    $response = call_user_func_array([$controller, $action], static::$params);
                    $controller->after($action);
                }
            } else {
                throw new \Exception("Route not found", 404);
            }
        } catch (\Exception $e) {
            $response = ['code' => $e->getCode(), 'body' => null, 'errors' => [$e->getMessage()]];
        }

        return json_response($response['code'], [
            'data' => $response['body'],
            'errors' => $response['errors']
        ]);
    }

    private static function getAction(Controller $controller): string
    {
        $action = static::$params['action'] ?? null;

        if (!method_exists($controller, $action)) {
            throw new \Exception("Method '$action' not found in controller '" . get_class($controller) . "'");
        }

        unset(static::$params['action']);

        return $action;
    }

    private static function getController(): Controller
    {
        $controllerName = static::$params['controller'] ?? null;

        if (!class_exists($controllerName)) {
            throw new \Exception("Controller '$controllerName' not found");
        }

        unset(static::$params['controller']);

        return new $controllerName;
    }

    private static function checkRequestMethod(): void
    {
        if (array_key_exists('method', static::$params)) {
            $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

            if ($requestMethod !== strtolower(static::$params['method'])) {
                throw new \Exception("Method '$requestMethod' is not allowed for this route");
            }

            unset(static::$params['method']);
        }
    }

    private static function match(string $uri): bool
    {
        foreach (static::$routes as $route => $params) {
            if (preg_match($route, $uri, $matches)) {
                static::$params = static::buildParams($route, $matches, $params);
                return true;
            }
        }

        return false;
    }

    private static function buildParams(string $route, array $matches, array $params): array
    {
        preg_match_all('/\(\?P<[\w]+>(\\\\)?([\w\.][\+]*)\)/', $route, $types);
        $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        if (!empty($types)) {
            $lastKey = array_key_last($types);
            $step = 0;
            $types[$lastKey] = array_map(fn($value) => str_replace('+', '', $value), $types[$lastKey]);

            foreach ($matches as $name => $match) {
                settype($match, static::$convertTypes[$types[$lastKey][$step]]);
                $params[$name] = $match;
                $step++;
            }
        }

        return $params;
    }

    private static function removeQueryVariables(string $uri): string
    {
        return preg_replace('/([\w\/\-]+)\?([\w\/=\d%*&\?]+)/i', '$1', $uri);
    }
}