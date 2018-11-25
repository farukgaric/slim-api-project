<?php declare(strict_types=1);

namespace App\Services;

class MessageRepository
{
    public $messages = [
        'COUNTRY-0001' => [
            'code'   => 'COUNTRY-0001',
            'status' => 201,
            'title'  => 'Country created',
            'detail' => 'Country creation succeed.'
        ],
        'COUNTRY-0002' => [
            'code'   => 'COUNTRY-0002',
            'status' => 500,
            'title'  => 'Unable to save country',
            'detail' => 'A database error has occurred. Unable to save country.'
        ],
        'COUNTRY-0003' => [
            'code'   => 'COUNTRY-0003',
            'status' => 404,
            'title'  => 'Country not found',
            'detail' => 'The requested country was not found on this server.'
        ],
        'COUNTRY-0004' => [
            'code'   => 'COUNTRY-0004',
            'status' => 200,
            'title'  => 'Country updated',
            'detail' => 'Country updated successfully.'
        ],
        'COUNTRY-0005' => [
            'code'   => 'COUNTRY-0005',
            'status' => 500,
            'title'  => 'Unable to update country information',
            'detail' => 'A database error has occurred or data is not changed'
        ],
        'COUNTRY-0006' => [
            'code'   => 'COUNTRY-0006',
            'status' => 200,
            'title'  => 'Country deleted',
            'detail' => 'Country deleted successfully.'
        ],
        'COUNTRY-0007' => [
            'code'   => 'COUNTRY-0007',
            'status' => 500,
            'title'  => 'Unable to delete country information',
            'detail' => 'A database error has occurred. Unable to delete country information.'
        ],
        'COUNTRY-0008' => [
            'code'   => 'COUNTRY-0008',
            'status' => 200,
            'title'  => 'Signin successful',
            'detail' => 'Signin successful.'
        ],
        'COUNTRY-0009' => [
            'code'   => 'COUNTRY-0009',
            'status' => 400,
            'title'  => 'Signin failed',
            'detail' => 'Signin failed. Check your email address and password.'
        ],
        'COUNTRY-0010' => [
            'code'   => 'COUNTRY-0010',
            'status' => 404,
            'title'  => 'Not found',
            'detail' => 'No results found.'
        ],
        
        // AUTH
        'AUTH-0001' => [
            'code'   => 'AUTH-0001',
            'status' => 500,
            'title'  => 'Internal authentication error',
            'detail' => 'Please provide a valid state (authenticated or anonymous) to check against.'
        ],
        'AUTH-0002' => [
            'code'   => 'AUTH-0002',
            'status' => 401,
            'title'  => 'Authentication error',
            'detail' => 'Access denied. You must be authorized to access the resource.'
        ],
        'AUTH-0003' => [
            'code'   => 'AUTH-0003',
            'status' => 403,
            'title'  => 'Authentication error',
            'detail' => 'Access denied. You must be anonymous in order to access the resource.'
        ],
        'AUTH-0004' => [
            'code'   => 'AUTH-0004',
            'status' => 401,
            'title'  => 'Authentication error',
            'detail' => 'Access denied. You must have valid authorization in order to access the resource.'
        ]
    ];
}
