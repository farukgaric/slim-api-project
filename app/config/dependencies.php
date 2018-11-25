<?php

/**
 * Fetch the container
 */
$container = $app->getContainer();

/**
 * Eloguent
 */

$container['db'] = function () {
    $connection = new Illuminate\Database\Capsule\Manager();
    $connection->addConnection([
        'driver' => 'mysql',
        'host' => $_SERVER['DB_HOST'] ?: 'localhost',
        'database' => $_SERVER['DB_NAME'] ?: 'db',
        'username' => $_SERVER['DB_USER'] ?: 'root',
        'password' => $_SERVER['DB_PASS'] ?: 'root',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'unix_socket' => getenv('DB_UNIX_SOCKET') ?: null,
    ]);
    $connection->setAsGlobal();
    $connection->bootEloquent();
    return $connection->getConnection();
};

/**
 * Content Renderer
 */
$container['renderer'] = function ($c) {
    return new RKA\ContentTypeRenderer\Renderer;
};

/**
 * Fractal
 */
$container['fractal'] = function ($c) {
    return new League\Fractal\Manager;
};

// Note that protected closures do not get access to the container
$container['collection'] = $container->protect(function ($collection, $callback) {
    return new League\Fractal\Resource\Collection($collection, $callback);
});

$container['item'] = $container->protect(function ($item, $callback) {
    return new League\Fractal\Resource\Item($item, $callback);
});

/**
 * JSON Web Tokens
 */
$container['jwt'] = function ($c) {
    return new Firebase\JWT\JWT;
};

/*
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};
*/

/**
 * Actions
 */
$container[App\Actions\Auth\Signin::class] = function ($c) {
    return new App\Actions\Auth\Signin($c->get('userRepository'), $c->get('auth'), $c->get('messages'), $c->get('jwt'), $c->get('renderer'));
};

$container[App\Actions\Users\ReadUser::class] = function ($c) {
    return new App\Actions\Users\ReadUser($c->get('userRepository'), $c->get('userTransformer'), $c->get('auth'), $c->get('jwt'), $c->get('messages'), $c->get('transformer'), $c->get('renderer'));
};

$container[App\Actions\Countries\CreateCountry::class] = function ($c) {
    return new App\Actions\Countries\CreateCountry($c->get('countryRepository'), $c->get('countryTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('renderer'));
};

$container[App\Actions\Countries\ReadCountry::class] = function ($c) {
    return new App\Actions\Countries\ReadCountry($c->get('countryRepository'), $c->get('countryTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('renderer'));
};

$container[App\Actions\Countries\ReadCountries::class] = function ($c) {
    return new App\Actions\Countries\ReadCountries($c->get('countryRepository'), $c->get('countryTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('renderer'));
};

$container[App\Actions\Countries\UpdateCountry::class] = function ($c) {
    return new App\Actions\Countries\UpdateCountry($c->get('countryRepository'), $c->get('countryTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('renderer'));
};

$container[App\Actions\Countries\RemoveCountry::class] = function ($c) {
    return new App\Actions\Countries\RemoveCountry($c->get('countryRepository'), $c->get('messages'), $c->get('renderer'));
};

/**
 * Domain
 */
$container['userModel'] = $container->factory(function ($c) {
    return new App\Domain\Users\User;
});

$container['userRepository'] = function ($c) {
    return new App\Domain\Users\UserRepository($c->get('userModel'));
};

$container['userTransformer'] = function ($c) {
    return new App\Domain\Users\UserTransformer;
};

$container['countryModel'] = $container->factory(function ($c) {
    return new App\Domain\Countries\Country;
});

$container['countryRepository'] = function ($c) {
    return new App\Domain\Countries\CountryRepository($c->get('countryModel'));
};

$container['countryTransformer'] = function ($c) {
    return new App\Domain\Countries\CountryTransformer;
};


/**
 * Middleware
 */
$container[App\Middleware\AuthMiddleware::class] = function ($c) {
    return new App\Middleware\AuthMiddleware($c->get('userRepository'), $c->get('auth'), $c->get('messages'), $c->get('jwt'), $c->get('renderer'));
};

$container[App\Middleware\ValidationMiddleware::class] = function ($c) {
    return new App\Middleware\ValidationMiddleware($c->get('validationRules'), $c->get('messages'), $c->get('renderer'));
};

$container['validationRules'] = function ($c) {
    return new App\Middleware\ValidationRules;
};


/**
 * Services
 */
$container['auth'] = function ($c) {
    return new App\Services\Auth($c->get('userRepository'), $c->get('jwt'));
};

$container['messageRepository'] = function ($c) {
    return new App\Services\MessageRepository;
};

$container['messages'] = function ($c) {
    return new App\Services\Messages($c->get('messageRepository'));
};

$container['transformer'] = function ($c) {
    return new App\Services\Transformer($c->get('fractal'));
};


$container->get('db');
