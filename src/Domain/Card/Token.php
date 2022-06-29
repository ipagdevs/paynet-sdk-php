<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class Token extends Card implements CardInterface
{
    private HolderName $holder;
    private CardNumber $number;
    private string $expiryMonth;
    private string $expiryYear;
    private HolderName $customerName;
    private string $securityCode;
    private int $brand;

    public function __construct(
        string $holder,
        string $customerName,
        string $number,
        string $expiryMonth,
        string $expiryYear
    ) {
        $this->holder = HolderName::fromString($holder);
        $this->customerName = HolderName::fromString($customerName);
        $this->number = CardNumber::fromString($number);

        $this->ensureIsValidExpiryMonth($expiryMonth);
        $this->expiryMonth = sprintf("%02d", $expiryMonth);

        $this->ensureIsValidExpiryYear($expiryYear);
        $this->expiryYear = sprintf("%02d", $expiryYear);
    }

    public static function fromValues(
        string $holder,
        string $customerName,
        string $number,
        string $expiryMonth,
        string $expiryYear
    ): self {
        return new self($holder, $customerName, $number, $expiryMonth, $expiryYear);
    }

    public function jsonSerialize(): array
    {
        return [
            'cardHolder' => (string) $this->holder,
            'customerName' => (string) $this->customerName,
            'cardNumber' => (string) $this->number,
            'expirationMonth' => $this->expiryMonth,
            'expirationYear' => $this->expiryYear,
        ];
    }

    public function token(): array
    {
        return [
            'numberToken' => (string) $this->token,
            'cardholderName' => (string) $this->holder,
            'securityCode' => (string) $this->securityCode,
            'brand' => (int) $this->brand,
            'expirationMonth' => $this->expiryMonth,
            'expirationYear' => $this->expiryYear,
        ];
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setSecurityCode(string $securityCode): void
    {
        $this->securityCode = $securityCode;
    }

    public function setBrand(int $brand): void
    {
        $this->brand = $brand;
    }
}
