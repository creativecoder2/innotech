<?php
// Simple diagnostic check for cPanel
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Innotech Medical Server Diagnostics</h2>";
echo "<b>PHP Version:</b> " . phpversion() . "<br>";
echo "<b>Vendor Folder (vendor/autoload.php):</b> " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? '<span style="color:green">EXISTS</span>' : '<span style="color:red">MISSING (Laravel cannot run without vendor)</span>') . "<br>";
echo "<b>.env File:</b> " . (file_exists(__DIR__ . '/../.env') ? '<span style="color:green">EXISTS</span>' : '<span style="color:red">MISSING</span>') . "<br>";
echo "<b>Storage Writable:</b> " . (is_writable(__DIR__ . '/../storage') ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "<br>";
echo "<b>Bootstrap Cache Writable:</b> " . (is_writable(__DIR__ . '/../bootstrap/cache') ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "<br>";

$extensions = ['pdo_mysql', 'mbstring', 'openssl', 'curl', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
echo "<h3>Required Extensions:</h3><ul>";
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<li>$ext: " . ($loaded ? '<span style="color:green">LOADED</span>' : '<span style="color:red">MISSING</span>') . "</li>";
}
echo "</ul>";
