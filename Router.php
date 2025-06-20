<?php

declare(strict_types=1);
require_once('./RouteNotFoundException.php');

class Router {
    private array $routes = [];

    // Ovo dodaš da znaš u kom je folderu aplikacija
    private string $basePath = '/programming_with_gio/061_http_headers';

    public function register(string $requestMethod, string $route, callable | array $action): self {
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function get(string $route, callable | array $action): self {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable | array $action): self {
        return $this->register('post', $route, $action);
    }

    public function routes(): array {
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod) {

        // 1. Skini query string        
        $route = explode('?', $requestUri)[0];        

        // 2. Ukloni base path
        $route = str_replace($this->basePath, '', $route);

        // 3. Ako je prazno, postavi na "/"
        if ($route === '' || $route === false) {
            $route = '/';
        }        

        // 4. Ukloni trailing slash, osim ako je ruta samo "/"
        $route = rtrim($route, '/') ?: '/';        

        $action = $this->routes[$requestMethod][$route] ?? null;        

        if (!$action) {
            throw new \App\RouteNotFoundException();
        }


        if(is_callable($action)) {
            return call_user_func($action);
        } 
        

        // Ako metoda nije static, pravi instancu

        if(is_array($action)) {
            [$class, $method] = $action;

            if(class_exists($class)) {
                $class = new $class();

                if(method_exists($class, $method)) {                    
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        throw new \App\RouteNotFoundException();

    }
}
