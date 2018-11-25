<?php

/**
 * Logged user's data
 * Endpoint: GET /account
 */
$app->get('/account', App\Actions\Users\ReadUser::class);

/**
 * Countries
 * Endpoints:
 * GET    /countries        Get all countries
 * POST   /countries        Create new country
 * GET    /countries/{id}   Get single country
 * PATCH  /countries/{id}   Update single country
 * DELETE /countries/{id}   Delete single country
 */
$app->group('/countries', function () {
    $this->post('', App\Actions\Countries\CreateCountry::class)
        ->setArguments(['validators' => ['name']]);

    $this->get('', App\Actions\Countries\ReadCountries::class)
        ->setArguments(['validators' => ['page' => ['int', true]]]);

    $this->group('/{id}', function () {
        $this->get('', App\Actions\Countries\ReadCountry::class)
            ->setArguments(['validators' => ['id' => ['int', true]]]);

        $this->patch('', App\Actions\Countries\UpdateCountry::class)
            ->setArguments(['validators' => ['id' => ['int', true]]]);

        $this->delete('', App\Actions\Countries\RemoveCountry::class)
            ->setArguments(['validators' => ['id' => ['int', true]]]);
    });
});

/**
 * Authentication
 * Endpoint: POST /auth     Generate authentication json web token (JWT)
 */
$app->group('/auth', function () {
    $this->post('', App\Actions\Auth\Signin::class)
        ->setArguments(['state' => 'anonymous', 'validators' => ['email', 'password', 'authMode' => [false, true]]]);
});
