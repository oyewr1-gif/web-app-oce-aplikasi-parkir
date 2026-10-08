<?php

class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        Session::init();
        $url = $this->parseUrl();

        // 1. Controller Resolution
        if (isset($url[0]) && !empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            if (file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Method Resolution
        if (isset($url[1]) && !empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Parameters Resolution
        $this->params = $url ? array_values($url) : [];

        // Call controller method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        // Priority 1: $_GET['url'] (set by Apache mod_rewrite / htaccess)
        if (isset($_GET['url']) && $_GET['url'] !== '') {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        // Priority 2: Fallback to $_SERVER['REQUEST_URI'] (for php -S CLI server or subfolder setups)
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = str_replace('\\', '/', $uri);

            // Strip subfolder scriptDir if URI starts with it
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            if ($scriptDir !== '/' && $scriptDir !== '.' && !empty($scriptDir)) {
                if (strpos($uri, $scriptDir) === 0) {
                    $uri = substr($uri, strlen($scriptDir));
                }
            }

            // Strip '/public' prefix if remaining URI starts with '/public'
            if (strpos($uri, '/public') === 0) {
                $uri = substr($uri, 7);
            }

            $uri = trim($uri, '/');
            if (!empty($uri)) {
                $uri = filter_var($uri, FILTER_SANITIZE_URL);
                return explode('/', $uri);
            }
        }

        return [];
    }
}
