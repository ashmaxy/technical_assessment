<?php

namespace App;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                $parts = explode('@', $route['controller']);
                $file = CONTROLLER_PATH . ucfirst($parts[0]) . 'Controller.php';

                if (file_exists($file)) {

                    require_once($file);
                    $controller = ucfirst($parts[0]) . 'Controller';

                    if (class_exists($controller)) {

                        $controller = new $controller();
                    } else {
                        $this->routeNotFound();
                    }

                    if (isset($parts[1])) {
                        $method = $parts[1];

                        if (!method_exists($controller, $method)) {
                            $this->routeNotFound();
                        }
                    } else {
                        $method = 'index';
                    }

                    // call to controller
                    if (is_callable([$controller, $method])) {
                        return call_user_func([$controller, $method]);
                    } else {
                        $this->routeNotFound();
                    }
                } else {
                    $this->routeNotFound();
                }
            }
        }

        $this->routeNotFound();
    }

    private function routeNotFound()
    {
        $GLOBALS['response']->setStatusCode(404);
        $GLOBALS['response']->setContent(['error' => 'Route not found', 'status_code' => 404]);
    }
}
