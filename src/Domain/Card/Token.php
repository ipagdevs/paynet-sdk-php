<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class Token implements CardInterface, \JsonSerializable
{
    private Card $card;
    private string $token;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function jsonSerialize(): array
    {
        return [
            'cardHolder' => (string) $this->card->holder(),
            'customerName' => (string) $this->card->customerName(),
            'cardNumber' => (string) $this->card->number(),
            'expirationMonth' => $this->card->expiryMonth(),
            'expirationYear' => $this->card->shortExpiryYear(),
        ];
    }

    public function token(): array
    {
        if (!isset($this->token)) {
            throw new \LogicException('Token is not set');
        }

        return [
            'numberToken' => (string) $this->token,
            'cardholderName' => (string) $this->card->holder(),
            'securityCode' => (string) $this->card->securityCode(),
            'brand' => (int) $this->card->brand(),
            'expirationMonth' => $this->card->expiryMonth(),
            'expirationYear' => $this->card->shortExpiryYear(),
        ];
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
