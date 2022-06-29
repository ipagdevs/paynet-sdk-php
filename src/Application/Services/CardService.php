<?php

namespace Paynet\Application;

use Paynet\Domain\Card\Token;
use Paynet\Domain\Card\Vault;
use Paynet\Domain\CardResponse;

class CardService extends PaynetService
{
    public function tokenize(Token $payload)
    {
        $response = $this->request('/card', 'POST', $payload);

        return CardResponse::createFromResponse($response);
    }

    public function vault(Vault $payload)
    {
        $response = $this->request("/vault", 'POST', $payload);

        return CardResponse::createFromResponse($response);
    }
}
