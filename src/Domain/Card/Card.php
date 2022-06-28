<?php

declare(strict_types=1);

namespace Paynet\Domain\Card;

class Card implements \JsonSerializable
{
    private HolderName $holder;
    private CardNumber $number;
    private string $expiryMonth;
    private string $expiryYear;
    private HolderName $customerName;

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

    private function ensureIsValidExpiryMonth(string $month): void
    {
        $monthAsInt = (int) $month;
        if (!is_numeric($month) || $monthAsInt < 1 || $monthAsInt > 12) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry month', $month));
        }
    }

    private function ensureIsValidExpiryYear(string $year): void
    {
        $yearAsInt = (int) $year;

        if (!preg_match("/^(\d{2})$/", $year)) {
            throw new \UnexpectedValueException(sprintf('%s is not a valid expiry year format (YY)', $year));
        }

        if (!is_numeric($year)) {
            throw new \UnexpectedValueException(sprintf('%s is not valid expiry year', $year));
        }
    }
}
