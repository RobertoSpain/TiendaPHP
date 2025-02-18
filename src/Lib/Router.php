<?php
namespace Lib;

class Router {

    private static $routes = [];

    /**
     * Agrega una ruta al arreglo
     */
    public static function add(string $method, string $action, callable $controller): void
    {
        // Quitamos slashes iniciales y finales
        $action = trim($action, '/');
        // Guardamos en el array
        self::$routes[$method][$action] = $controller;
    }

    /**
     * Despacha la ruta solicitada
     */
    public static function dispatch(): void
    {
        error_log("Despachando ruta: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);

        $method = $_SERVER['REQUEST_METHOD'];

        // 1) parseamos la URL
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = $parsedUrl['path'] ?? '/';

        // 2) si tu proyecto está en /miTienda, quitamos ese prefijo
        $path = preg_replace('#^/miTienda#', '', $path);

        // 3) quitamos slashes
        $path = trim($path, '/');

        // 4) separamos en segmentos y buscamos dígitos finales
        $segments = explode('/', $path);
        $params = [];

        // Revisamos de derecha a izquierda si hay números
        while (!empty($segments) && preg_match('#^\d+$#', end($segments))) {
            $params[] = array_pop($segments);
        }
        // Los revertimos para que queden en orden
        $params = array_reverse($params);

        // 5) la parte restante (sin dígitos) será la "clave de ruta"
        $action = implode('/', $segments);

        // Buscamos en self::$routes
        // Ojo: con la lógica actual, "carrito/add/13" -> action="carrito/add"
        $fn = self::$routes[$method][$action] ?? null;

        if ($fn) {
            // Llamamos a la función con los parámetros numéricos
            echo call_user_func_array($fn, $params);
        } else {
            // Ruta no encontrada
            error_log("Ruta NO encontrada: $method /$action con params=[".implode(',', $params)."]");
        }
    }

}
