<?php
namespace Core;

class App {
    protected $controller = 'AuthController'; // Default controller (Login)
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check Controller
        if (file_exists(ROOT_PATH . '/app/Controllers/' . ucfirst($url[0] ?? '') . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once ROOT_PATH . '/app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        $this->params = $url ? array_values($url) : [];

        // Call method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}