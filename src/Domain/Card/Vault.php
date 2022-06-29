<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

use Paynet\Domain\Card\Card;

class Vault implements CardInterface, \JsonSerializable
{
    /**
     * Vault expiration (in minutes)
     */
    const VALIDATE = 60;

    private Card $card;
    private string $token;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function jsonSerialize(): array
    {
        return [
            'cardNumber' => (string) $this->card->number(),
            'cardHolder' => (string) $this->card->holder(),
            'expirationMonth' => $this->card->expiryMonth(),
            'expirationYear' => $this->card->expiryYear(),
            'brand' => (int) $this->card->brand(),
            'validate' => (int) self::VALIDATE
        ];
    }

    public function token(): array
    {
        if (!isset($this->token)) {
            throw new \LogicException('Token is not set');
        }

        return [
            'vaultId' => (string) $this->token
        ];
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
