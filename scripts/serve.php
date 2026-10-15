<?php

// Keep local PHP upload limits aligned with the application's 100 MB limit.
$root = dirname(__DIR__);
$router = $root.'/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
if (! is_file($router)) {
    fwrite(STDERR, "Install Composer dependencies before starting the server.\n");
    exit(1);
}

chdir($root.'/public');
$host = getenv('TICKET_SERVER_HOST') ?: '127.0.0.1';
$port = (int) (getenv('TICKET_SERVER_PORT') ?: 8000);
if (! filter_var($host, FILTER_VALIDATE_IP) || $port < 1 || $port > 65535) {
    fwrite(STDERR, "Use a valid IP and port for the local server.\n");
    exit(1);
}
$arguments = [
    PHP_BINARY,
    '-d', 'upload_max_filesize=100M',
    '-d', 'post_max_size=110M',
    '-d', 'max_input_time=600',
    '-d', 'max_execution_time=600',
    '-S', $host.':'.$port,
    $router,
];
fwrite(STDOUT, "Local server: http://{$host}:{$port} (100 MB uploads, 110 MB requests)\n");
passthru(implode(' ', array_map('escapeshellarg', $arguments)), $status);
exit($status);
