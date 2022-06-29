<?php

declare(strict_types=1);

namespace Paynet\Application;

use Paynet\Domain\Response;
use Paynet\Domain\Operation;
use Paynet\Domain\Transaction;
use Paynet\Application\Http\Request;

class TransactionService
{
    public static function headers()
    {
        return Request::defaultHeaders();
    }

    public function authorize(Request $api, Transaction $payload)
    {
        $response = $api->post('/financial', $payload, self::headers());

        return Response::createFromResponse($response);
    }

    public function capture(Request $api, Operation $payload)
    {
        $response = $api->post('/capture', $payload, self::headers());

        return Response::createFromResponse($response);
    }

    public function cancel(Request $api, Operation $payload)
    {
        $response = $api->post('/cancel', $payload, self::headers());

        return Response::createFromResponse($response);
    }

    public function consult(Request $api, string $orderNumber)
    {
        $response = $api->post('/getTransaction', ['orderNumber' => $orderNumber], self::headers());

        return Response::createFromResponse($response);
    }
}
