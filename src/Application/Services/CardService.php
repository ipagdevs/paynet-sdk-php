<?php

declare(strict_types=1);

namespace Paynet\Application;

use Paynet\Domain\Card\Token;
use Paynet\Domain\Card\Vault;
use Paynet\Domain\CardResponse;
use Paynet\Application\Http\Request;

class CardService
{
    public static function headers()
    {
        return Request::defaultHeaders();
    }

    public static function tokenize(Request $api, Token $payload)
    {
        $response = $api->post('/card', $payload, self::headers());

        return CardResponse::createFromResponse($response);
    }

    public static function vault(Request $api, Vault $payload)
    {
        $response = $api->post("/vault", $payload, self::headers());

        return CardResponse::createFromResponse($response);
    }
}
