<?php
header('Content-Type: text/plain; charset=utf-8');
echo "Step 0: PHP is alive (" . date('H:i:s') . ")\n";

$start = microtime(true);

echo "Step 1: Loading Composer autoloader...\n";
require dirname(__DIR__) . '/vendor/autoload.php';
echo "Step 1 OK in " . round((microtime(true) - $start) * 1000, 2) . " ms\n";

$t = microtime(true);
echo "Step 2: Bootstrapping Laravel application...\n";
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
echo "Step 2 OK in " . round((microtime(true) - $t) * 1000, 2) . " ms\n";

$t = microtime(true);
echo "Step 3: Testing Database Connection...\n";
try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Step 3 OK (Database connected) in " . round((microtime(true) - $t) * 1000, 2) . " ms\n";
} catch (\Throwable $e) {
    echo "Step 3 FAILED (Database Error: " . $e->getMessage() . ")\n";
}

$t = microtime(true);
echo "Step 4: Testing Settings Query...\n";
try {
    $title = \App\Models\Setting::get('site_title', 'default');
    echo "Step 4 OK (Site title: " . $title . ") in " . round((microtime(true) - $t) * 1000, 2) . " ms\n";
} catch (\Throwable $e) {
    echo "Step 4 FAILED: " . $e->getMessage() . "\n";
}

echo "ALL STEPS COMPLETED in " . round((microtime(true) - $start) * 1000, 2) . " ms!\n";
