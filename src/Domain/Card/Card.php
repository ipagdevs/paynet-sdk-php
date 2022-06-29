<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class Card
{
    const MASTERCARD = 1;
    const VISA = 3;
    const ELO = 5;
    const HIPERCARD = 7;
    const AMEX = 9;

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
        string $expiryYear,
        string $securityCode,
        int $brand
    ) {
        $this->holder = HolderName::fromString($holder);
        $this->customerName = HolderName::fromString($customerName);
        $this->number = CardNumber::fromString($number);

        $this->ensureIsValidExpiryMonth($expiryMonth);
        $this->expiryMonth = sprintf("%02d", $expiryMonth);

        $this->ensureIsValidExpiryYear($expiryYear);
        $this->expiryYear = sprintf("%02d", $expiryYear);

        $this->ensureIsValidBrand($brand);
        $this->brand = $brand;

        //$this->ensureIsValidSecurityCode($securityCode);
        $this->securityCode = $securityCode;
    }

    public static function fromValues(
        string $holder,
        string $customerName,
        string $number,
        string $expiryMonth,
        string $expiryYear,
        string $securityCode,
        int $brand
    ): self {
        return new self($holder, $customerName, $number, $expiryMonth, $expiryYear, $securityCode, $brand);
    }

    protected function ensureIsValidExpiryMonth(string $month): void
    {
        $monthAsInt = (int) $month;
        if (!is_numeric($month) || $monthAsInt < 1 || $monthAsInt > 12) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry month', $month));
        }
    }

    protected function ensureIsValidExpiryYear(string $year): void
    {
        if (!preg_match("/^(\d{2})$/", $year)) {
            throw new \UnexpectedValueException(sprintf('%s is not a valid expiry year format (YY)', $year));
        }

        if (!is_numeric($year)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry year', $year));
        }
    }

    protected function ensureIsValidBrand(int $brand): void
    {
        $brands = [
            self::MASTERCARD,
            self::VISA,
            self::ELO,
            self::HIPERCARD,
            self::AMEX,
        ];

        if (!in_array($brand, $brands)) {
            throw new \UnexpectedValueException(sprintf('%d is not valid brand', $brand));
        }
    }

    protected function ensureIsValidSecurityCode(string $securityCode): void
    {
        if (!preg_match("/^(\d{3-4})$/", $securityCode)) {
            throw new \UnexpectedValueException(sprintf('%s is not a valid security code', $securityCode));
        }
    }

    public function holder(): HolderName
    {
        return $this->holder;
    }

    public function customerName(): HolderName
    {
        return $this->customerName;
    }

    public function number(): CardNumber
    {
        return $this->number;
    }

    public function expiryMonth(): string
    {
        return $this->expiryMonth;
    }

    public function expiryYear(): string
    {
        return $this->expiryYear;
    }

    public function securityCode(): string
    {
        return $this->securityCode;
    }

    public function brand(): int
    {
        return $this->brand;
    }
}
