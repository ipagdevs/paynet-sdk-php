<?php

declare(strict_types=1);

namespace Paynet\Application\Services;

use Paynet\Domain\Card\Token;
use Paynet\Domain\Card\Vault;
use Paynet\Domain\CardResponse;
use Paynet\Application\Http\Request;

class CardService
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

    public static function tokenize(Request $api, Token $payload): CardResponse
    {
        $response = $api->post('/card', $payload, self::headers($api->apiKey()));

        return CardResponse::createFromResponse($response);
    }

    public static function vault(Request $api, Vault $payload): CardResponse
    {
        $response = $api->post("/vault", $payload, self::headers($api->apiKey()));

        return CardResponse::createFromResponse($response);
    }
}
