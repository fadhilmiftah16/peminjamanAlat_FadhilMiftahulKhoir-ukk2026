<?php
class App {
    protected $controller = 'auth';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if (isset($url[0]) && file_exists('../app/controllers/' . strtolower($url[0]) . 'controller.php')) {
            $this->controller = strtolower($url[0]);
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . 'controller.php';

        $className = ucfirst($this->controller) . 'Controller';
        $this->controller = new $className;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
