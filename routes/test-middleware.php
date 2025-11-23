<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-middleware', function () {
    $kernel = app()->make(\App\Http\Kernel::class);
    $middleware = $kernel->getRouteMiddleware();

    echo "<h1>Middleware Registration Test</h1>";

    $roleMiddleware = ['admin', 'rt', 'kasi', 'lurah', 'masyarakat'];

    foreach ($roleMiddleware as $mw) {
        if (isset($middleware[$mw])) {
            echo "<p style='color: green'>✅ $mw: " . $middleware[$mw] . "</p>";
        } else {
            echo "<p style='color: red'>❌ $mw: NOT REGISTERED</p>";
        }
    }

    echo "<hr>";
    echo "<p><a href='/admin/dashboard'>Test Admin Dashboard</a></p>";
    echo "<p><a href='/'>Home</a></p>";
});
