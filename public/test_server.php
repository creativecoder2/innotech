<?php
header('Content-Type: text/html; charset=utf-8');
echo "<h2>Server Diagnostics</h2>";
echo "<b>Time:</b> " . date('Y-m-d H:i:s') . "<br>";
echo "<b>PHP Version:</b> " . phpversion() . "<br>";
echo "<b>mbstring:</b> " . (extension_loaded('mbstring') ? '<span style="color:green">ENABLED</span>' : '<span style="color:red">DISABLED</span>') . "<br>";
echo "<b>opcache:</b> " . (extension_loaded('Zend OPcache') ? '<span style="color:green">ENABLED</span>' : '<span style="color:red">DISABLED</span>') . "<br>";
echo "<b>pdo_mysql:</b> " . (extension_loaded('pdo_mysql') ? '<span style="color:green">ENABLED</span>' : '<span style="color:red">DISABLED</span>') . "<br>";

// Test Database Connection
$envFile = dirname(__DIR__) . '/.env';
if (!file_exists($envFile)) {
    $envFile = dirname(__DIR__) . '/.env.production';
}

echo "<hr><h3>Environment & Database Test:</h3>";
if (file_exists($envFile)) {
    echo "Using file: " . htmlspecialchars(basename($envFile)) . "<br>";
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            list($key, $val) = explode('=', $line, 2);
            $val = trim($val, " \t\n\r\0\x0B\"'");
            $env[trim($key)] = $val;
        }
    }

    $host = $env['DB_HOST'] ?? 'localhost';
    $port = $env['DB_PORT'] ?? '3306';
    $db   = $env['DB_DATABASE'] ?? '';
    $user = $env['DB_USERNAME'] ?? '';
    $pass = $env['DB_PASSWORD'] ?? '';

    echo "Host: {$host}:{$port} | DB: {$db} | User: {$user} | Pass length: " . strlen($pass) . "<br>";

    $start = microtime(true);
    try {
        $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db}", $user, $pass, [
            PDO::ATTR_TIMEOUT => 3,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $duration = round((microtime(true) - $start) * 1000, 2);
        echo "<b style='color:green'>MySQL Connected Successfully in {$duration} ms!</b><br>";
    } catch (\Throwable $e) {
        $duration = round((microtime(true) - $start) * 1000, 2);
        echo "<b style='color:red'>MySQL Connection FAILED in {$duration} ms: " . htmlspecialchars($e->getMessage()) . "</b><br>";
    }
} else {
    echo "<b style='color:red'>ERROR: Neither .env nor .env.production exists on server!</b><br>";
}
