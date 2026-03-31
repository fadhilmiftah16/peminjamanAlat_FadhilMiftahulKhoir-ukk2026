<?php

class Controller {
    public function view($view, $data = []) {
        $file = "../app/views/" . $view . ".php";
        
        if (file_exists($file)) {
            require_once $file;
        } else {
            die("Halaman View <b>$view</b> tidak ditemukan!");
        }
    }

    public function model($model) {
        $file = '../app/models/' . $model . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $model;
        } else {
            die("File Model <b>$model</b> tidak ditemukan!");
        }
    }
}