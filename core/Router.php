<?php
class Router {
    protected $routes = [];

    public function add($method, $route, $controller, $action) {
        $this->routes[] = ['method' => $method, 'route' => $route, 'controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action) { $this->add('GET', $route, $controller, $action); }
    public function post($route, $controller, $action) { $this->add('POST', $route, $controller, $action); }

    public function dispatch($url) {
        $url = strtok($url, '?');
        if (empty($url)) { $url = '/'; }
        if ($url != '/' && substr($url, 0, 1) != '/') { $url = '/' . $url; }
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        foreach ($this->routes as $route) {
            if ($route['method'] == $method && $route['route'] == $url) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                
                require_once "controllers/$controllerName.php";
                $controller = new $controllerName();
                $controller->$actionName();
                return;
            }
        }
        
        http_response_code(404);
        echo "404 Not Found: $url";
    }
}
