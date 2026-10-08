<?php
// Configuration File for Database Connection & Environment Loader

if (!function_exists('loadEnv')) {
    function loadEnv($filePath) {
        if (!file_exists($filePath)) {
            return;
        }
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($key, $val) = explode('=', $line, 2);
                $key = trim($key);
                $val = trim($val);
                if ((strpos($val, '"') === 0 && substr($val, -1) === '"') || 
                    (strpos($val, "'") === 0 && substr($val, -1) === "'")) {
                    $val = substr($val, 1, -1);
                }
                if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                    putenv("{$key}={$val}");
                    $_ENV[$key] = $val;
                    $_SERVER[$key] = $val;
                }
            }
        }
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $val = getenv($key);
        if ($val === false) {
            $val = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        }
        if ($val === 'true' || $val === '(true)') return true;
        if ($val === 'false' || $val === '(false)') return false;
        if ($val === 'null' || $val === '(null)') return null;
        return $val;
    }
}

// Load environment variables from .env file
loadEnv(__DIR__ . '/../.env');

// Database Credentials from .env
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'parkir_db'));
define('DB_PORT', env('DB_PORT', '3306'));

// Universal Dynamic BASE_URL definition (with .env override support)
$envBaseUrl = env('BASE_URL');
if (!empty($envBaseUrl)) {
    define('BASE_URL', rtrim($envBaseUrl, '/'));
} else {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($scriptDir === '/' || $scriptDir === '.') {
        $scriptDir = '';
    }
    define('BASE_URL', rtrim($protocol . '://' . $host . $scriptDir, '/'));
}

define('APP_NAME', env('APP_NAME', 'Sistem Manajemen Parkir MVC'));
