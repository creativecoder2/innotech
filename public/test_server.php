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

if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    $host = $env['DB_HOST'] ?? 'localhost';
    $port = $env['DB_PORT'] ?? '3306';
    $db   = $env['DB_DATABASE'] ?? '';
    $user = $env['DB_USERNAME'] ?? '';
    $pass = $env['DB_PASSWORD'] ?? '';

    echo "<hr><h3>Database Connection Test:</h3>";
    echo "Host: {$host}:{$port} | DB: {$db} | User: {$user}<br>";

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
    echo "<br><i>No .env file found to test DB.</i><br>";
}
