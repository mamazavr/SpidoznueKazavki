<?php

namespace Core;

class Router
{
    protected static array $routes = [];

    public static function add(string $route, array $params): void
    {
        static::$routes['/^' . str_replace('/', '\\/', $route) . '$/i'] = $params;
    }

    public static function dispatch(string $uri): void
    {
        foreach (static::$routes as $route => $params) {
            if (preg_match($route, $uri, $matches)) {
                array_shift($matches);
                return;
            }
        }
    }
}
