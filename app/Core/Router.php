<?php

namespace Fylari\Core;

class Router
{
    private array $routes = [
        'GET' => [], 'POST' => [], 'PUT' => [], 'DELETE' => []
    ];

    public function get(string $uri, array $action)    { $this->addRoute('GET', $uri, $action); }
    public function post(string $uri, array $action)   { $this->addRoute('POST', $uri, $action); }
    public function put(string $uri, array $action)    { $this->addRoute('PUT', $uri, $action); }
    public function delete(string $uri, array $action) { $this->addRoute('DELETE', $uri, $action); }

    public function addRoute(string $method, string $uri, array $action): void
    {
        $pattern = preg_replace('#\{([\w]+)\}#', '([^/]+)', $uri);
        $this->routes[$method]["#^$pattern$#"] = $action;
    }

    public function registerModule(string $name, string $controller): void
    {
        $base = '/' . strtolower($name);
        $this->get("$base", [$controller, 'index']);
        $this->get("$base/{id}", [$controller, 'show']);
        $this->post("$base", [$controller, 'store']);
        $this->put("$base/{id}", [$controller, 'update']);
        $this->delete("$base/{id}", [$controller, 'destroy']);
        $this->put("$base/{id}/archive", [$controller, 'archive']);
        $this->put("$base/{id}/restore", [$controller, 'restore']);
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        $this->lazyLoadModuleRoutes($uri);

        foreach ($this->routes[$method] as $pattern => $action) {
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controller, $methodName] = $action;
                if (class_exists($controller)) {
                    call_user_func_array([new $controller(), $methodName], $matches);
                    return;
                }
                http_response_code(500);
                echo "Controller $controller not found.";
                return;
            }
        }

        http_response_code(404);
        echo "Route not found.";
    }

    private function lazyLoadModuleRoutes(string $uri): void
    {
        $segments = explode('/', trim($uri, '/'));
        $moduleName = ucfirst($segments[0] ?? '');

        if (!$moduleName) return;

        $moduleDir = __DIR__ . "/../Modules/$moduleName";
        $controllerClass = "Fylari\\Modules\\$moduleName\\{$moduleName}Controller";
        //echo $moduleDir; var_dump(file_exists("$moduleDir/{$moduleName}Controller.php"));
        if (!class_exists($controllerClass) && file_exists("$moduleDir/{$moduleName}Controller.php")) {
            require_once "$moduleDir/{$moduleName}Controller.php";
        }
        //var_dump(class_exists($controllerClass));die();
        // 🧠 Получаем тип модуля из БД
        $module = \Fylari\Core\DB::table('modules')->where('code', '=', strtolower($moduleName))->first();

        // ✅ Если модуль найден и тип "entity", подключаем CRUD
        if ($module && $module['type'] === 'entity') {
            if (class_exists($controllerClass)) {
                $this->registerModule($moduleName, $controllerClass);
            }
        }

        // ✅ Подключаем кастомные маршруты всегда
        $routeFile = "$moduleDir/Routes.php";
        if (file_exists($routeFile)) {
            /** @var Router $router */
            $router = $this;
            require_once $routeFile;
        }
    }

}
