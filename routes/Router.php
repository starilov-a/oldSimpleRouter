<?php

namespace Routes;

class Router {
    public static $routes;

    public function __construct() {
        $this->route();
    }

    public static function add($path, $method, $action) {
        self::$routes[$path] = ['method' => $method, 'action' => $action];
    }

    private function route() {
        foreach (self::$routes as $path => $goto) {
            if ($path == $_SERVER['REQUEST_URI']) {
                if($this->detecteMethod($goto['method'])) {
                    list($class, $action) = $this->getClass($goto['action']);

                    $obj = new $class;

                    return $obj->$action();
                }
            }
        }

        echo '404';
        return false;
    }

    private function detecteMethod($method) {
        return (strtoupper($method) == $_SERVER['REQUEST_METHOD']);
    }

    private function getClass($goto) {
        list($path, $action) = explode('@', $goto);
        foreach (explode('.',$path) as $routeStep) {
            $routeArr[] = ucfirst(strtolower($routeStep));
        }
        $class = implode('\\', $routeArr);

        return ['\\'.$class, $action];

    }

}