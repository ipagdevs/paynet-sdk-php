<?php

declare(strict_types=1);

namespace Paynet\Application\Services;

use Paynet\Application\Credentials;
use Paynet\Domain\Login;
use Paynet\Application\Http\Request;

class LoginService
{
    public static function headers()
    {
        return [
            'Route' => Request::ROUTE,
        ];
    }

    public static function login(Request $api, Credentials $credentials): Login
    {
        $response = $api->post('/login', $credentials, self::headers());
        $loginResponse = Login::createFromResponse($response);

        return $loginResponse;
    }
}
