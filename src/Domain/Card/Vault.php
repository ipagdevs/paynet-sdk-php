<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class Vault extends Card implements CardInterface
{
    /**
     * Vault expiration (in minutes)
     */
    const VALIDATE = 60;

    const VAULT = 'vault';
    const TOKEN = 'token';

    private HolderName $holder;
    private CardNumber $number;
    private string $expiryMonth;
    private string $expiryYear;
    private string $token;
    private int $brand;

    public function __construct(
        string $holder,
        string $number,
        string $expiryMonth,
        string $expiryYear,
        int $brand
    ) {
        $this->holder = HolderName::fromString($holder);
        $this->number = CardNumber::fromString($number);

        $this->ensureIsValidExpiryMonth($expiryMonth);
        $this->expiryMonth = sprintf("%02d", $expiryMonth);

        $this->ensureIsValidExpiryYear($expiryYear);
        $this->expiryYear = sprintf("%02d", $expiryYear);

        $this->ensureIsValidBrand($brand);
        $this->brand = $brand;
    }

    public static function fromValues(
        string $holder,
        string $number,
        string $expiryMonth,
        string $expiryYear,
        int $brand
    ): self {
        return new self($holder, $number, $expiryMonth, $expiryYear, $brand);
    }

    public function jsonSerialize(): array
    {
        return [
            'cardNumber' => (string) $this->number,
            'cardHolder' => (string) $this->holder,
            'expirationMonth' => $this->expiryMonth,
            'expirationYear' => $this->expiryYear,
            'brand' => (int) $this->brand,
            'validate' => (int) self::VALIDATE
        ];
    }

    public function token(): array
    {
        return [
            'vaultId' => (string) $this->token
        ];
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
