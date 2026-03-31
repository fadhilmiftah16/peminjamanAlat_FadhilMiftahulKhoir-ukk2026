<?php
if (!session_id()) session_start();

class App {
    protected $controller = 'AdminController'; 
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();

        // 1. CEK CONTROLLER
        if (!empty($url) && isset($url[0])) {
            // Kita paksa huruf depan jadi kapital agar sesuai dengan nama file/class
            $controllerName = ucfirst($url[0]); 
            
            if (file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // 2. REQUIRE FILE
        // Kita panggil file controller-nya
        require_once '../app/controllers/' . $this->controller . '.php';

        // 3. INSTANSIASI (Membuat Object)
        // Jika class tidak ditemukan, ini akan memberikan pesan error yang jelas
        if (class_exists($this->controller)) {
            $this->controller = new $this->controller;
        } else {
            die("Error: Class " . $this->controller . " tidak ditemukan di dalam file!");
        }

        // 4. CEK METHOD
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 5. PARAMS (Parameter)
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // JALANKAN CONTROLLER & METHOD
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        // Biarkan kosong jika tidak ada URL, jangan diisi default di sini
        return [];
    }
}