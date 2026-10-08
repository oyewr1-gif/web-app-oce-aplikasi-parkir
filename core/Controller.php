<?php

class Controller {
    // Render view file
    public function view($view, $data = []) {
        $viewPath = '../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            die("View file not found: " . $viewPath);
        }
    }

    // Instantiate model
    public function model($model) {
        $modelPath = '../app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Model file not found: " . $modelPath);
        }
    }

    // Redirect helper
    public function redirect($url) {
        if (strpos($url, 'http') === 0) {
            $target = $url;
        } else {
            $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
            $target = $baseUrl . '/' . ltrim($url, '/');
        }
        header('Location: ' . $target);
        exit;
    }
}
