<?php
class Router {
    public function dispatch() {
        $url = $_GET['url'] ?? 'auth/login';
        $url = explode('/', $url);
        $controllerName = ucfirst($url[0]) . 'Controller';
        $method = $url[1] ?? 'index';

        require_once BASE_PATH . '/app/controllers/' . $controllerName . '.php';
        $controller = new $controllerName();
        call_user_func([$controller, $method]);
    }
}
?>
