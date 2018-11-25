<?php

// Config
require __DIR__ . '/config/config.php';

// App
require __DIR__ . '/config/app.php';

// Dependencies
require __DIR__ . '/config/dependencies.php';

// Middleware
require __DIR__ . '/config/middleware.php';

// Routes
require __DIR__ . '/config/routes.php';

// Run
$app->run();
