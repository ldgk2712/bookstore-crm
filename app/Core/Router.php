<?php

/**
 * Router đơn giản: map METHOD + PATH -> [Controller, action].
 * Mọi request đi qua public/index.php sẽ được Router này xử lý.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path, array $container): void
    {
        // Kiểm tra path có tồn tại ở BẤT KỲ method nào không (để phân biệt 404 vs 405)
        $pathExistsInOtherMethod = false;
        foreach ($this->routes as $registeredMethod => $paths) {
            if ($registeredMethod !== $method && array_key_exists($path, $paths)) {
                $pathExistsInOtherMethod = true;
                break;
            }
        }

        if (isset($this->routes[$method][$path])) {
            [$controllerClass, $action] = $this->routes[$method][$path];
            $controller = $container[$controllerClass] ?? new $controllerClass();
            $controller->$action();
            return;
        }

        if ($pathExistsInOtherMethod) {
            http_response_code(405);
            render('errors/405', ['title' => '405 Method Not Allowed']);
            return;
        }

        http_response_code(404);
        render('errors/404', ['title' => '404 Not Found']);
    }
}
