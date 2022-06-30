<?php

declare(strict_types=1);

namespace Paynet\Application\Services;

use Paynet\Domain\Response;
use Paynet\Domain\Operation;
use Paynet\Domain\Transaction;
use Paynet\Application\Http\Request;

class TransactionService
{
    public static function headers($auth)
    {
        return array_merge(
            Request::defaultHeaders(),
            [
                'Authorization' => sprintf("%s", $auth),
            ]
        );
    }

    public static function authorize(Request $api, Transaction $payload)
    {
        $response = $api->post('/financial', $payload, self::headers($api->apiKey()));

        return Response::createFromResponse($response);
    }

    public static function capture(Request $api, Operation $payload)
    {
        $response = $api->post('/capture', $payload, self::headers($api->apiKey()));

        return Response::createFromResponse($response);
    }

    public static function cancel(Request $api, Operation $payload)
    {
        $response = $api->post('/cancel', $payload, self::headers($api->apiKey()));

        return Response::createFromResponse($response);
    }

    public static function consult(Request $api, string $orderNumber)
    {
        $response = $api->post('/getTransaction', ['orderNumber' => $orderNumber], self::headers($api->apiKey()));

        return Response::createFromResponse($response);
    }
}
