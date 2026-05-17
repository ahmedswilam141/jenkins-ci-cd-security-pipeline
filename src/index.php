<?php

if (php_sapi_name() === 'cli') {
    passthru('php -S 0.0.0.0:8080 -t ' . escapeshellarg(__DIR__));
    exit;
}

echo "Service App is running successfully.";
