<?php
namespace Paynet\Application;

use Paynet\Domain\Card\Card;
use Paynet\Domain\CardResponse;

class CardService extends PaynetService
{
    public function tokenize(Card $payload)
    {
        $response = $this->request('/card', 'POST', $payload);

        return CardResponse::createFromResponse($response);
    }
    
    public function vault(Card $payload)
    {
        $response = $this->request("/vault", 'POST', $payload);

        return CardResponse::createFromResponse($response);
    }
}