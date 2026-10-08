<?php
// Handle static files when running under PHP CLI built-in web server
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($filePath)) {
        return false;
    }
}

require_once '../config/database.php';
require_once '../core/Database.php';
require_once '../core/Session.php';
require_once '../core/Controller.php';
require_once '../core/App.php';

// Initialize Application Router
$app = new App();
